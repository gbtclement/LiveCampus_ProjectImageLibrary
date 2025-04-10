<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;


final class PublicController extends AbstractController
{
    #[Route('/public', name: 'app_public', methods: ['GET', 'POST'])]
    public function getImages(Request $request, EntityManagerInterface $em, HttpClientInterface $client): Response
    {

        dump($request->getMethod());
        // Créer et gérer le formulaire d'upload d'image
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($form->isSubmitted());
            dump($form->isValid());
            dump($form->getErrors(true));
            dump($form->get('imageFile')->getData());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('imageFile')->getData();
            $name = $form->get('name')->getData();

            $formData = [
                'name' => $name,
                'imageFile' => DataPart::fromPath($uploadedFile->getPathname(), $uploadedFile->getClientOriginalName()),
            ];

            $formDataPart = new FormDataPart($formData);

            $response = $client->request('POST', 'http://localhost:8002/api/public/images', [
                'headers' => $formDataPart->getPreparedHeaders()->toArray(),
                'body' => $formDataPart->bodyToIterable(),
            ]);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $this->addFlash('success', 'Image envoyée via l’API');
            } else {
                $this->addFlash('error', 'Erreur API');
            }

            return $this->redirectToRoute('app_public');
        }

        $response = $client->request('GET', 'http://localhost:8002/api/public/images');
        $images = $response->toArray();

        return $this->render('public/index.html.twig', [
            'images' => $images,
            'form' => $form->createView(),
        ]);
    }
}


