<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Client;

class ClientTest extends AbstractTest
{
    public function testGetCollection(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', 'api/clients');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context'         => '/api/contexts/Client',
            '@id'              => '/api/clients',
            '@type'            => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id'         => '/api/clients?page=1',
                '@type'       => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/clients?page=1',
                'hydra:last'  => '/api/clients?page=4',
                'hydra:next'  => '/api/clients?page=2',
            ],
        ]);

        $this->assertCount(30, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Client::class);
    }

    public function testCreateClient(): void
    {
        $response = static::createClient()->request(
            'POST',
            'api/clients',
            [
                'json' => [
                    'firstName'   => 'John',
                    'lastName'    => 'Doe',
                    'email'       => 'some@example.mail',
                    'phoneNumber' => '+19799788092',
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context'    => '/api/contexts/Client',
            '@type'       => 'Client',
            'firstName'   => 'John',
            'lastName'    => 'Doe',
            'email'       => 'some@example.mail',
            'phoneNumber' => '+19799788092',
        ]);
        $this->assertMatchesRegularExpression('~^/api/clients/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Client::class);
    }

    public function testCreateInvalidClient(): void
    {
        static::createClient()->request(
            'POST',
            'api/clients',
            [
                'json' => [
                    'firstName'   => 'John',
                    'lastName'    => 'Doe',
                    'email'       => 'wrong-mail',
                    'phoneNumber' => '+19799788092',
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context'          => '/api/contexts/ConstraintViolationList',
            '@type'             => 'ConstraintViolationList',
            'hydra:title'       => 'An error occurred',
            'hydra:description' => 'email: This value is not a valid email address.',
        ]);
    }

    public function testUpdateClient(): void
    {
        $client = static::createClient();
        $iri    = $this->findIriBy(Client::class, ['email' => 'scorkery@hessel.com']);

        $client->request(
            'PUT',
            $iri,
            [
                'json' => [
                    'firstName' => 'Updated',
                ],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id'       => $iri,
            'firstName' => 'Updated',
        ]);
    }

    public function testDeleteClient(): void
    {
        $client = static::createClient();
        $iri    = $this->findIriBy(Client::class, ['email' => 'scorkery@hessel.com']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::$kernel
                ->getContainer()
                ->get('doctrine')
                ->getRepository(Client::class)
                ->findOneBy(['email' => 'scorkery@hessel.com'])
        );
    }
}
