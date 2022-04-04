<?php

declare(strict_types=1);

namespace App\Notification;

use App\Entity\Notification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificationProvider implements NotificationProviderInterface
{
    public const TYPE = 'email';

    public function __construct(private MailerInterface $mailer)
    {
    }

    public function send(Notification $notification): void
    {
        $client = $notification->getClient();

        $email = (new Email())
            ->to($client->getEmail())
            ->text($notification->getContent());

        $this->mailer->send($email);
    }
}