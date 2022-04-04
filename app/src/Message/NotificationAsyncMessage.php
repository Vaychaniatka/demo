<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Notification;

class NotificationAsyncMessage
{
    public function __construct(private Notification $notification)
    {
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
