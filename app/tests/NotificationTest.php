<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Client;
use App\Entity\Notification;

class NotificationTest extends AbstractTest
{
    public function testGetCollection(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', 'api/notifications');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context'         => '/api/contexts/Notification',
            '@id'              => '/api/notifications',
            '@type'            => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id'         => '/api/notifications?page=1',
                '@type'       => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/notifications?page=1',
                'hydra:last'  => '/api/notifications?page=4',
                'hydra:next'  => '/api/notifications?page=2',
            ],
        ]);

        $this->assertCount(30, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Notification::class);
    }

    public function testCreateNotification(): void
    {
        $iri = $this->findIriBy(Client::class, ['email' => 'scorkery@hessel.com']);

        $response = $this
            ->createClientWithCredentials()
            ->request(
                'POST',
                'api/notifications',
                [
                    'json' => [
                        'client'   => $iri,
                        'channel'  => 'sms',
                        'content'  => 'some notification content',
                    ]
                ]
            );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Notification',
            '@type'    => 'Notification',
            'client'   => $iri,
            'channel'  => 'sms',
            'content'  => 'some notification content',
        ]);
        $this->assertMatchesRegularExpression('~^/api/notifications/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Notification::class);
    }

    public function testCreateInvalidClient(): void
    {
        $iri = $this->findIriBy(Client::class, ['email' => 'scorkery@hessel.com']);

        $this
            ->createClientWithCredentials()
            ->request(
                'POST',
                'api/notifications',
                [
                    'json' => [
                        'client'  => $iri,
                        'channel' => 'wrongValue',
                        'content' => 'some notification content',
                    ]
                ]
        );

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context'          => '/api/contexts/ConstraintViolationList',
            '@type'             => 'ConstraintViolationList',
            'hydra:title'       => 'An error occurred',
            'hydra:description' => 'channel: The value you selected is not a valid choice.',
        ]);
    }
}
