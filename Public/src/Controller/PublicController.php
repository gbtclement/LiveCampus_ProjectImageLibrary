<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PublicController extends AbstractController
{
    #[Route('/public', name: 'app_public')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('imageFile')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $image->setUrl('/uploads/' . $newFilename);
            }

            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('app_public');
        }

        $images = $em->getRepository(Image::class)->findAll();

        return $this->render('public/index.html.twig', [
            'images' => $images,
            'form' => $form->createView(),
        ]);
    }
}
