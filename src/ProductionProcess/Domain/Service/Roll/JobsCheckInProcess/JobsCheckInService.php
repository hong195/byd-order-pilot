<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\JobsCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\Job;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Repository\JobRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Job\SortService as JobSortService;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\ProductionProcess\Domain\ValueObject\FilmType;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\ProductionProcess\Domain\ValueObject\Status;
use App\ProductionProcess\Infrastructure\Repository\RollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangeJobService.
 */
final class JobsCheckInService implements JobCheckInInterface
{
    private Collection $assignedRolls;
    private Collection $rolls;
    private Collection $jobs;

    /**
     * Class constructor.
     *
     * @param JobRepositoryInterface        $jobRepository        The job repository
     * @param JobSortService                $sortJobsService      The sort jobs service
     * @param RollRepository                $rollRepository       The roll repository
     * @param AvailableFilmServiceInterface $availableFilmService The available film service
     * @param RollMaker                     $rollMaker            The roll maker
     */
    public function __construct(
        private readonly JobRepositoryInterface $jobRepository,
        private readonly JobSortService $sortJobsService,
        private readonly RollRepository $rollRepository,
        private readonly AvailableFilmServiceInterface $availableFilmService,
        private readonly RollMaker $rollMaker,
    ) {
    }

    /**
     * Performs the check-in process for the current session.
     *
     * @throws \Exception if an error occurs during the check-in process
     */
    public function checkIn(): void
    {
        $this->initData();

        $availableFilms = $this->availableFilmService->getAvailableFilms();
        $groupedFilms = $this->groupFilmsByType($availableFilms);
        $groupedJobs = $this->groupJobsByFilm($this->jobs);

        foreach ($groupedJobs as $filmType => $jobs) {
            if (!isset($groupedFilms[$filmType])) {
                // If there is no film of this type, create an empty roll for all jobs of this type
                foreach ($jobs as $job) {
                    $roll = $this->findOrMakeRoll(name: "Empty Roll {$job->getFilmType()->value}", filmType: $job->getFilmType());
                    $roll->addJob($job);
                    $this->syncAssignRolls($roll);
                }
                continue;
            }

            // Инициализируем доступные пленки для данного типа
            $currentFilm = $groupedFilms[$filmType];

            foreach ($jobs as $job) {
                $jobPlaced = false;

                // Attempting to place a job on existing film rolls
                foreach ($currentFilm as $key => $film) {
                    $filmLength = $film->length;

                    $roll = $this->findOrMakeRoll(name: "Roll {$film->filmType}", filmId: $film->id, filmType: $job->getFilmType());

                    if ($roll->getJobsLength() + $job->getLength() <= $filmLength) {
                        $roll->addJob($job);

                        $this->syncAssignRolls($roll);
                        $jobPlaced = true;

                        if (0 === $filmLength) {
                            unset($currentFilm[$key]); // Remove the film from the available films
                        }

                        break;
                    }
                }

                // Если заказ не был размещен, создаем пустой рулон
                if (!$jobPlaced) {
                    $roll = $this->findOrMakeRoll("Empty Roll {$job->getFilmType()->value}", null, $job->getFilmType());
                    $roll->addJob($job);

                    $this->syncAssignRolls($roll);
                }
            }
        }

        $this->saveRolls();
    }

    /**
     * Initializes the data for the jobs check in, uses latest rolls and jobs to do that.
     */
    private function initData(): void
    {
        $this->assignedRolls = new ArrayCollection([]);
        $this->rolls = new ArrayCollection($this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN)));

        $this->initJobs();
    }

    /**
     * Finds or makes a roll based on the given parameters.
     *
     * @param string        $name     The name of the roll
     * @param int|null      $filmId   The ID of the film associated with the roll (optional)
     * @param FilmType|null $filmType The roll type associated with the roll (optional)
     *
     * @return Roll The found or newly created roll
     */
    private function findOrMakeRoll(string $name, ?int $filmId = null, ?FilmType $filmType = null): Roll
    {
        $roll = $this->rolls->filter(function (Roll $roll) use ($filmId, $filmType) {
            return $roll->getFilmId() === $filmId && in_array($filmType, $roll->getPrinter()?->getFilmTypes() ?? []);
        })->first();

        if ($roll) {
            return $roll;
        }

        $roll = $this->rollMaker->make($name, $filmId, $filmType, Process::ORDER_CHECK_IN);

        $this->rolls->add($roll);

        return $roll;
    }

    /**
     * Syncs the assigned rolls with a new roll.
     *
     * @param Roll $roll The roll to sync with
     */
    private function syncAssignRolls(Roll $roll): void
    {
        // if roll was added previously to assignedRolls, remove it and add it again
        if ($this->assignedRolls->contains($roll)) {
            $this->assignedRolls->removeElement($roll);
        }

        $this->assignedRolls->add($roll);
    }

    /**
     * Groups jobs by roll type.
     *
     * @param Collection<Job> $jobs the collection of jobs
     *
     * @return array<string, Job[]> the array of grouped jobs
     */
    private function groupJobsByFilm(Collection $jobs): array
    {
        $groupedJobs = [];

        foreach ($jobs as $job) {
            $groupedJobs[$job->getFilmType()->value][] = $job;
        }

        return $groupedJobs;
    }

    /**
     * Groups films by roll type.
     *
     * @param Collection<FilmData> $films the collection of films
     *
     * @return array<string, FilmData[]> the array of grouped films
     */
    private function groupFilmsByType(Collection $films): array
    {
        $groupedFilms = [];

        foreach ($films as $film) {
            $groupedFilms[$film->filmType][] = $film;
        }

        return $groupedFilms;
    }

    /**
     * Initializes the jobs in the application.
     *
     * This method retrieves the jobs with status "assignable" from the job repository,
     * adds them to the $jobs collection, and then adds the jobs associated with each
     * roll in the $rolls collection to the $jobs collection. Finally, it sorts the
     * $jobs collection using the SortJobsService.
     */
    private function initJobs(): void
    {
        $this->jobs = new ArrayCollection();
        $assignableJobs = $this->jobRepository->findByStatus(Status::ASSIGNABLE);

        foreach ($assignableJobs as $job) {
            $this->jobs->add($job);
        }

        /** @var Roll $roll */
        foreach ($this->rolls as $roll) {
            foreach ($roll->getJobs() as $job) {
                $this->jobs->add($job);
            }

            $roll->removeJobs();
        }

        $this->jobs = $this->sortJobsService->getSorted($this->jobs);
    }

    /**
     * Saves the assigned rolls.
     *
     * If a roll has no jobs associated with it, it will be removed from the repository.
     */
    private function saveRolls(): void
    {
        foreach ($this->rolls as $roll) {
            $this->syncAssignRolls($roll);
        }

        foreach ($this->assignedRolls as $roll) {
            if ($roll->getJobs()->isEmpty()) {
                $this->rollRepository->remove($roll);
                continue;
            }

            $this->rollRepository->save($roll);
        }
    }
}
