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
        $reponse = $this->user->request('GET', 'http://localhost:8002/api/images');
        $images = $reponse->toArray();

        return $this->render('admin/list.html.twig', [
            'images' => $images
        ]);
    }

    #[Route('/admin/image/{id}', name: 'app_admin_image', methods:['GET'])]
    public function image($id) {
        $response = $this->user->request('GET', 'http://localhost:8002/api/image/' . $id);
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

    #[Route('/admin/image/hit/{id}', name: 'app_admin_image_hit', methods:['POST'])]
    public function addHit($id): Response
    {
        $this->user->request('POST', 'http://localhost:8002/api/hit/' . $id);
        
        return $this->redirectToRoute('app_admin_image', ['id' => $id]);
    }

}
