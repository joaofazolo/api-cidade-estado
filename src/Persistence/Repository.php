<?php

namespace App\Persistence;

use MongoDB\Client;
use Psr\Container\ContainerInterface;

class Repository
{
    protected $client;

    public function __construct(ContainerInterface $container)
    {
        $database = $container->get('settings')['database'];
        $this->client = (new Client("mongodb://" . $database['user'] . ":" . $database['password'] . "@" . $database['host']))->zoox;
    }
}