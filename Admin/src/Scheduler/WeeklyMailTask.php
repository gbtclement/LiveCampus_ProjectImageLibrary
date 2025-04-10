<?php
namespace App\Scheduler;

use App\Scheduler\Message\MailStatistics;
use App\Service\ImageService;
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
        private HttpClientInterface $client,
        private ImageService $imageService,
    ) {}

    public function getSchedule(): Schedule
    {
        $images = $this->imageService->fetchImages();
        // voir pour appeler une fonction à la place de this->bus->dispatch
        
        return (new Schedule())->add(
            RecurringMessage::every('5 seconds', $this->bus->dispatch(new MailStatistics($images))),
        );
    }
}
