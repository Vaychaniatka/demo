<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\NotificationAsyncMessage;
use App\Notification\NotificationProviderFactory;
use App\Repository\NotificationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NotificationHandler
{
    public function __construct(
        private NotificationProviderFactory $providerFactory,
        private NotificationRepository $repository
    ) {
    }

    public function __invoke(NotificationAsyncMessage $message)
    {
        $notification = $message->getNotification();

        $provider = $this->providerFactory->getProvider($notification->getChannel());

        if ($provider) {
            $provider->send($notification);

            $this->repository->markAsSent($notification->getId());
        }
    }
}
