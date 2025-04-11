<?php
namespace App\Command;

use App\Scheduler\Message\MailStatistics;
use App\Service\ImageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('lc:email','permet d\'envoyer un email avec des statistiques sur les images.')]
final class SendEmailCommand extends Command
{   
    public function __construct(
        private MessageBusInterface $bus,
        private HttpClientInterface $client,
        private ImageService $imageService

    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $images = $this->imageService->fetchImages();

        $this->bus->dispatch(new MailStatistics($images));

        return Command::SUCCESS;
    }
}
