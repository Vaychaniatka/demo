<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Notification;
use App\Message\NotificationAsyncMessage;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationCreatedNotifier
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function postPersist(Notification $notification, LifecycleEventArgs $arguments): void
    {
        $this->bus->dispatch(
            new NotificationAsyncMessage($notification)
        );
    }
}
