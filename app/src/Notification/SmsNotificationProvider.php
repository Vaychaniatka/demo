<?php

declare(strict_types=1);

namespace App\Notification;

use App\Entity\Notification;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class SmsNotificationProvider implements NotificationProviderInterface
{
    public const TYPE = 'sms';

    public function __construct(private TexterInterface $texter)
    {
    }

    public function send(Notification $notification): void
    {
        $client = $notification->getClient();

        $sms = new SmsMessage(
            $client->getPhoneNumber(),
            $notification->getContent()
        );

        $this->texter->send($sms);
    }
}