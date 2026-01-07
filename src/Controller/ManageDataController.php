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
    private function getEntityRelationFields(string $entityClass, EntityManagerInterface $em): array
    {
        $metadata = $em->getClassMetadata($entityClass);
        $relationFields = [];
        foreach ($metadata->getAssociationMappings() as $fieldName => $mapping) {
            $relationFields[] = $fieldName;
        }
        return $relationFields;
    }

    #[Route('/export', name: 'data.export')]
    public function exportJsonAction(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {

        $entitiesToExport = [
            'collaborator' => ['class' => \App\Entity\Collaborator::class, 'group' => 'collaborator'],
            'project' => ['class' => \App\Entity\Project::class, 'group' => 'project'],
            'projectTechnology' => ['class' => \App\Entity\ProjectTechnology::class, 'group' => 'projectTechnology'],
            'school' => ['class' => \App\Entity\School::class, 'group' => 'school'],
            'society' => ['class' => \App\Entity\Society::class, 'group' => 'society'],
            'technology' => ['class' => \App\Entity\Technology::class, 'group' => 'technology'],
            'trophy' => ['class' => \App\Entity\Trophy::class, 'group' => 'trophy'],
            'trophyRoad' => ['class' => \App\Entity\TrophyRoad::class, 'group' => 'trophyRoad'],
            'tag' => ['class' => \App\Entity\Tag::class, 'group' => 'tag'],
        ];

        $zipFile = tempnam(sys_get_temp_dir(), 'export_') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($entitiesToExport as $filename => $entityData) {
            $data = $em->getRepository($entityData['class'])->findAll();

            $genericCallback = function ($innerObject) {
                // Cas 1: Collection (many-to-many, one-to-many) -> array d'IDs
                if ($innerObject instanceof \Doctrine\Common\Collections\Collection) {
                    return $innerObject->map(fn($item) => $item->getId())->toArray();
                }
                
                // Cas 2: Objet avec getId (many-to-one) -> ID simple
                if (is_object($innerObject) && method_exists($innerObject, 'getId')) {
                    return $innerObject->getId();
                }
                
                // Cas 3: Autre (null, scalaire, etc.) -> retourner tel quel
                return $innerObject;
            };
            

            $relationFields = $this->getEntityRelationFields($entityData['class'], $em);
            $callbacks = [];
            foreach ($relationFields as $field) {
                $callbacks[$field] = $genericCallback;
            }

            $context = [
                'groups' => [$entityData['group']],
                \Symfony\Component\Serializer\Normalizer\AbstractNormalizer::CALLBACKS => $callbacks,
                'circular_reference_handler' => function ($object) {
                    return method_exists($object, 'getId') ? $object->getId() : null;
                },
            ];

            $json = $serializer->serialize($data, 'json', $context);
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
