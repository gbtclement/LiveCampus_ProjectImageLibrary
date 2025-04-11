<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ImageController extends AbstractController
{
    #[Route('/api/image/{id}', name: 'app_image', methods: ['GET'])]
    public function image(ImageRepository $imageRepository, string $id): JsonResponse
    {
        // Utilisation du repository pour récupérer l'image par ID
        $image = $imageRepository->getImageById($id);

        // Si l'image n'est pas trouvée, on retourne une erreur 404
        if (empty($image)) {
            return new JsonResponse(['error' => 'Image not found'], 404);
        }

        // Retourner l'image sous forme de JSON
        return new JsonResponse($image[0]); // On retourne la première image trouvée (en cas de multiple résultats)
    }

    #[Route('/api/images/stats', name: 'api_images_stats')]
    public function stats(ImageRepository $imageRepository): JsonResponse
    {
        $stats = $imageRepository->getImagesWithHitCount();
    
        return $this->json($stats);
    }
}
