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

#[AsSchedule('email')]
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
        
        return (new Schedule())->add(
            RecurringMessage::every('1 week', $this->bus->dispatch(new MailStatistics($images))),
        );
    }
}
