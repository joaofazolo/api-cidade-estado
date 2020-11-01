<?php

namespace App\Persistence;

use MongoDB\Client;

class Repository
{
    protected $client;

    public function __construct($database)
    {
        $this->client = (new Client("mongodb://" . $database['user'] . ":" . $database['password'] . "@" . $database['host']))->zoox;
    }
}