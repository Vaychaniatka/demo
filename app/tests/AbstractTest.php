<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

abstract class AbstractTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    private string $token = 'API_TOKEN';

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials(): Client
    {
        return static::createClient([], ['headers' => ['X-AUTH-TOKEN' => $this->token]]);
    }
}
