<?php
namespace App\Scheduler;

use App\Scheduler\Message\MailStatistics;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('test')]
class WeeklyMailTask implements ScheduleProviderInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private HttpClientInterface $client
    ) {}

    public function getSchedule(): Schedule
    {
        $response = $this->client->request('GET', 'https://localhost:8002/api/images', [
            'verify_peer' => false,
            'verify_host' => false,
        ]);
        $images = $response->toArray();
        // voir pour appeler une fonction à la place de this->bus->dispatch
        
        return (new Schedule())->add(
            RecurringMessage::every('5 seconds', $this->bus->dispatch(new MailStatistics($images))),
        );
    }
}
