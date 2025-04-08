<?php
namespace App\Scheduler\MessageHandler;

use App\Scheduler\Message\MailStatistics;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class MailStatisticsHandler
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }
    public function __invoke(MailStatistics $message)
    {
        $email = (new TemplatedEmail())
        ->from('admlivecampus@gmail.com')
        ->to('zakaria.saber@hotmail.fr')
        ->subject('Rapport hebdomadaire des images les plus téléchargées')
        ->htmlTemplate('message/stats_report.html.twig')
        ->context([
            'images' => $message->getImages()
        ]);

        $this->mailer->send($email);
        echo "Email envoyé !"; 
    }
}