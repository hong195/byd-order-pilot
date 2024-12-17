<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service;

use App\Shared\Domain\Factory\MediaFileFactory;
use App\Shared\Domain\Repository\MediaFileRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadFileService
{
    public const UPLOAD_DIR = 'uploads';

    /**
     * Constructs a new instance of the class.
     *
     * @param MediaFileFactory             $mediaFileFactory the media file factory object
     * @param FilePathService              $filePathService  the file path service object
     * @param MediaFileRepositoryInterface $fileRepository   the media file repository object
     */
    public function __construct(
        private MediaFileFactory $mediaFileFactory,
        private FilePathService $filePathService,
        private MediaFileRepositoryInterface $fileRepository,
    ) {
    }

    /**
     * Uploads a file.
     *
     * @param File|UploadedFile $file      the file to upload
     * @param int|null          $ownerId   the ID of the owner of the file (optional)
     * @param string|null       $ownerType the type of the owner of the file (optional)
     * @param string|null       $type      the type of the file (optional)
     *
     * @return string the ID of the uploaded file
     */
    public function upload(File|UploadedFile $file, ?int $ownerId = null, ?string $ownerType = null, ?string $type = null): string
    {
        /* @phpstan-ignore-next-line */
        $fileOriginalName = $file->getClientOriginalName();

        $path = $this->filePathService->generatePath($fileOriginalName);
        $file->move(self::UPLOAD_DIR, $path);

        $file = $this->mediaFileFactory->make(
            filename: $fileOriginalName,
            source: 'local',
            path: $path,
            type: $type,
            ownerId: $ownerId,
            ownerType: $ownerType
        );

        $this->fileRepository->save($file);

        return $file->getId();
    }
}
