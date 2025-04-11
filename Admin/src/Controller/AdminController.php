<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class AdminController extends AbstractController
{

    public function __construct(
        private HttpClientInterface $user,
    ) {}

    #[Route('/admin', name: 'app_admin', methods:['GET','POST'])]
    public function index() {

        return $this->render('admin/home.html.twig', [
            'controller_name' => "test"
        ]);
    }

    #[Route('/admin/list', name: 'app_admin_list', methods:['GET'])]
    public function imageList() {
        $response = $this->user->request('GET', 'http://localhost:8002/api/images');
        $images = $response->toArray();

        return $this->render('admin/list.html.twig', [
            'images' => $images
        ]);
    }

    #[Route('/admin/image/{id}', name: 'app_admin_image', methods:['GET'])]
    public function image($id) {
        $response = $this->user->request('GET', "http://localhost:8002/api/image/{$id}");
        $image = $response->toArray();
        return $this->render('admin/image.html.twig', [
            'image' => $image
        ]);
    }

    #[Route('/admin/image/delete/{id}', name: 'app_admin_image_delete', methods:['POST'])]
    public function delete($id): Response
    {
        $this->user->request('GET', 'http://localhost:8002/api/delete/' . $id);
        
        return $this->redirectToRoute('app_admin_list');
    }


    #[Route('/admin/stats', name: 'admin_stats')]
    public function test_stats(HttpClientInterface $client): Response
    {
        $response = $client->request('GET', 'http://localhost:8002/api/images/stats');
        $stats = $response->toArray();

        $labels = array_column($stats, 'name');
        $data = array_column($stats, 'hit_count');

        return $this->render('admin/stats.html.twig', [
                'labels' => $labels,
                'data' => $data,
                'imagesStats' => $stats,
            ]);
    }
}
