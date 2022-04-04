<?php

declare(strict_types=1);

namespace App\Notification;

use App\Entity\Notification;

interface NotificationProviderInterface
{
    public function send(Notification $notification): void;
}
