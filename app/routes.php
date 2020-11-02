<?php

use App\Application\Controllers\CidadeController;
use App\Application\Controllers\EstadoController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->post('/estado', EstadoController::class . ':insert');
    $app->get('/estado', EstadoController::class . ':index');
    $app->get('/estado/{id}', EstadoController::class . ':show');
    $app->put('/estado/{id}', EstadoController::class . ':update');
    $app->delete('/estado/{id}', EstadoController::class . ':delete');

    $app->post('/cidade', CidadeController::class . ':insert');
    $app->get('/cidade', CidadeController::class . ':index');
    $app->get('/cidade/{id}', CidadeController::class . ':show');
    $app->put('/cidade/{id}', CidadeController::class . ':update');
    $app->delete('/cidade/{id}', CidadeController::class . ':delete');

    $app->get('/home', function ($request, $response, $args) {
            return $this->get('view')->render($response, 'home.php', []);
    });
};
