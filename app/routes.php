<?php

use App\Application\Controllers\CidadeController;
use App\Application\Controllers\EstadoController;
use App\Application\Middleware\ApiKeyMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

/**
 * @OA\Info(
 *      title="API de cidades e estados", 
 *      version="1.0",
 *      description="API para CRUD de cidades e estados desenvolvida em php, slim e mongodb",
 *      @OA\Contact(email="jvfazolo@gmail.com", name="João Vitor Fazolo")
 * )
 */

 /**
 * @OA\Server(url="http://localhost:8080")
 */

 /**
 * @OA\SecurityScheme(
 *   securityScheme="apiKey",
 *   type="apiKey",
 *   in="header",
 *   name="X-Api-Key"
 * )
 */

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });
    $app->group('', function () use ($app) {
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
    })->add(new ApiKeyMiddleware($app->getContainer()));
    

    $app->get('/pesquisarCidade', function ($request, $response, $args) {
        return $this->get('view')->render($response, 'cidade.php', []);
    });

    $app->get('/pesquisarEstado', function ($request, $response, $args) {
        return $this->get('view')->render($response, 'estado.php', []);
    });
};
