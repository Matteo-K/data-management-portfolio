<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/data')]
class ManageDataController extends AbstractController
{
    #[Route('/export', name: 'data.export')]
    public function exportJsonAction(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $entitiesToExport = [
            'collaborator' => \App\Entity\Collaborator::class,
            'projet' => \App\Entity\Project::class,
            'projetTechnology' => \App\Entity\ProjectTechnology::class,
            'school' => \App\Entity\School::class,
            'society' => \App\Entity\Society::class,
            'technology' => \App\Entity\Technology::class,
            'trophy' => \App\Entity\Trophy::class,
            'trophyRoad' => \App\Entity\TrophyRoad::class,
        ];

        $zipFile = tempnam(sys_get_temp_dir(), 'export_') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($entitiesToExport as $filename => $entityClass) {
            $data = $em->getRepository($entityClass)->findAll();

            $json = $serializer->serialize($data, 'json', [
                'groups' => ['export'],
                'circular_reference_handler' => function ($object) {
                    return method_exists($object, 'getId') ? $object->getId() : null;
                },
            ]);

            $zip->addFromString($filename . '.json', $json);
        }

        $zip->close();

        $response = new StreamedResponse(function () use ($zipFile) {
            readfile($zipFile);
            unlink($zipFile);
        });

        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment; filename="entities.zip"');

        return $response;
    }

    #[Route('/export/files', name: 'data.export.files')]
    public function exportFilesAction(): Response
    {
        $sourceDir = $this->getParameter('kernel.project_dir') . '/public/image';
        $zipFile = tempnam(sys_get_temp_dir(), 'images_') . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($sourceDir) + 1);
                    $zip->addFile($filePath, 'images/' . $relativePath);
                }
            }

            $zip->close();
        }

        $response = new StreamedResponse(function () use ($zipFile) {
            readfile($zipFile);
            unlink($zipFile);
        });

        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment; filename="uploads.zip"');

        return $response;
    }
}
