<?php

namespace App\Controller;

use App\Scheduler\Message\MailStatistics;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MessageController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        private ImageService $imageService,
    ) {}

    #[Route('/test', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('message/test.html.twig');
    }
    
    #[Route('/message', name: 'app_message')]
    public function index(MessageBusInterface $bus): Response
    {
        $images = $this->imageService->fetchImages();

        $bus->dispatch(new MailStatistics($images));

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}
