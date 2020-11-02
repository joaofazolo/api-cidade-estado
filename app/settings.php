<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'database' => [
                'host' => isset($_ENV['db_host']) ? $_ENV['db_host'] : 'localhost',
                'user' => isset($_ENV['db_user']) ? $_ENV['db_user'] : 'root',
                'password' => isset($_ENV['db_password']) ? $_ENV['db_password'] : 'root'
            ],
            'secutiry' => [
                'api_keys' => [
                    'chave_segura_da_api',
                    'chav_segura_da_api2'
                ]
            ]
        ],
    ]);
};
