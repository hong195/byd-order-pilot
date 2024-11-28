<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/file/download/{filename}', name: 'file_download', requirements: ['filename' => '^(\w+).(pdf|jpg|jpeg|png|gif)$'], methods: ['GET'])]
final class DownloadFileController extends AbstractController
{
    public function __invoke(string $filename): BinaryFileResponse
    {
        $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/'.$filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Файл не найден');
        }

        $response = new BinaryFileResponse($filePath);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}
