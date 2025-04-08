<?php
namespace App\Scheduler;

use App\Scheduler\Message\MailStatistics;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsPeriodicTask(frequency: 60)]
class WeeklyMailTask
{
    public function __construct(
        private MessageBusInterface $bus,
        private HttpClientInterface $client
    ) {}

    public function __invoke(): void
    {
        $response = $this->client->request('GET', 'https://localhost:8002/api/images', [
            'verify_peer' => false,
            'verify_host' => false,
        ]);
        $images = $response->toArray();

        $this->bus->dispatch(new MailStatistics($images));
    }
}
