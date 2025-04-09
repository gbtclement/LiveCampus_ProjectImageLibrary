<?php
namespace App\Scheduler\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
class MailStatistics
{
    public function __construct(
        private ?array $images,
    ) {
    }

    public function getImages(): array
    {
        return $this->images;
    }
}