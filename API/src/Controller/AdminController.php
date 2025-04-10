<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ImageRepository;
use App\Repository\HitRepository;
use Symfony\Component\HttpFoundation\JsonResponse;


final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/api/images', name: 'app_list', methods: ['GET'])]
    public function list(ImageRepository $image)
    {
        return new JsonResponse($image->getAllImages());
    }

    #[Route('/api/image/{id}', name: 'app_image', methods: ['GET'])]
    public function image(ImageRepository $image, string $id)
    {
        return new JsonResponse($image->getImageById($id));
    }

    #[Route('/api/delete/{id}', name: 'app_delete', methods: ['GET','POST'])]
    public function delete(ImageRepository $image, string $id)
    {
        return new JsonResponse($image->deleteImageById($id));
    }

    #[Route('/api/hit/{id}', name: 'app_hit', methods: ['GET','POST'])]
    public function addHitOnImage(ImageRepository $imageRepository, HitRepository $hitRepository, int $id)
    {
        $image = $imageRepository->find($id);   
        return new JsonResponse($hitRepository->addHit($image));
    }
}
