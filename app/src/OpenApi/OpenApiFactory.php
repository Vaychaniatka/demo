<?php

declare(strict_types=1);

namespace App\OpenApi;


use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $clientPathItem                   = $openApi->getPaths()->getPath('/api/clients');
        $notificationPathItem             = $openApi->getPaths()->getPath('/api/notifications');
        $notificationParametrizedPathItem = $openApi->getPaths()->getPath('/api/notifications/{id}');

        $clientOperationGet        = $clientPathItem->getGet();
        $notificationOperationGet  = $notificationPathItem->getGet();
        $notificationOperationPost = $notificationPathItem->getPost();

        $tokenParameter = [
            new Model\Parameter('X-AUTH-TOKEN', 'header', 'Api token', true),
        ];

        $openApi
            ->getPaths()
            ->addPath(
                '/api/notifications',
                $notificationPathItem->withGet(
                    $notificationOperationGet->withParameters(
                        array_merge(
                            $notificationOperationGet->getParameters(),
                            $tokenParameter
                        )
                    )
                )->withPost(
                    $notificationOperationPost->withParameters(
                        array_merge(
                            $notificationOperationPost->getParameters(),
                            $tokenParameter
                        )
                    )
                )
            );

        $openApi
            ->getPaths()
            ->addPath(
                '/api/notifications/{id}',
                $notificationParametrizedPathItem->withGet(
                    $notificationOperationGet->withParameters(
                        array_merge(
                            $notificationOperationGet->getParameters(),
                            $tokenParameter
                        )
                    )
                )
            );

        $openApi
            ->getPaths()
            ->addPath(
                '/api/clients',
                $clientPathItem->withGet(
                    $clientOperationGet->withParameters(
                        array_merge(
                            $clientOperationGet->getParameters(),
                            $tokenParameter
                        )
                    )
                )
            );

        $openApi->withServers([new Model\Server('http://demo-api.local', 'Demo API')]);

        return $openApi;
    }
}
