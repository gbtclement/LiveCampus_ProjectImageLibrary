<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ImagePublic;

class PublicApiController extends AbstractController
{
    #[Route('/api/public/images', name: 'api_public_images_get', methods: ['GET'])]
    public function getImages(ImageRepository $imageRepository): JsonResponse
    {
        $images = $imageRepository->findAll();

        $data = array_map(function ($image) {
            return [
                'name' => $image->getName(),
                'url' => $image->getUrl()
            ];
        }, $images);

        return $this->json($data);
    }

    #[Route('/api/public/images', name: 'api_public_images_post', methods: ['POST'])]
    public function postImages(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $name = $request->request->get('name');
        $uploadedFile = $request->files->get('imageFile');

        if (!$uploadedFile) {
            return new JsonResponse(['error' => 'Fichier manquant'], 400);
        }

        $filename = uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $uploadedFile->move($this->getParameter('images_directory'), $filename);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'enregistrement du fichier.'], 500);
        }

        $image = new ImagePublic();
        $image->setName($name);
        $image->setUrl('/uploads/' . $filename);

        $em->persist($image);
        $em->flush();

        return new JsonResponse([
            'message' => 'Image enregistrée avec succès',
            'url' => $image->getUrl(),
            'name' => $image->getName(),
        ], 201);
    }
}