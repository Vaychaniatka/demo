<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\DependencyInjection\ContainerInterface;

class NotificationProviderFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getProvider(string $type): ?NotificationProviderInterface
    {
        $provider =  match ($type) {
            EmailNotificationProvider::TYPE => $this->container->get(EmailNotificationProvider::class),
            SmsNotificationProvider::TYPE   => $this->container->get(SmsNotificationProvider::class),
        };

        return $provider instanceof NotificationProviderInterface ? $provider : null;
    }
}
