<?php
namespace App\Command;

use App\Scheduler\Message\MailStatistics;
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
        private HttpClientInterface $client
    ) {
        parent::__construct();
    }
    // Créer une class services et injecter mon service ici et faire appel à la méthode 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('GET', 'https://localhost:8002/api/images', [
            'verify_peer' => false,
            'verify_host' => false,
        ]);
        $images = $response->toArray();

        $this->bus->dispatch(new MailStatistics($images));

        return 0;
    }
}
