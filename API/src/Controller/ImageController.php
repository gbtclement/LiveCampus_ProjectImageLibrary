<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }

    #[Route('/api/images/stats', name: 'api_images_stats')]
    public function stats(ImageRepository $imageRepository): JsonResponse
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
}
