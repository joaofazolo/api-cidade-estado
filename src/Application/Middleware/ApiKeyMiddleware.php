<?php

namespace App\Application\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class ApiKeyMiddleware implements MiddlewareInterface
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $validApiKeys = $this->container->get('settings')['secutiry']['api_keys'];
        $apiKey = $request->getHeader('X-Api-Key');
        if (empty($apiKey)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['message' => 'Favor fornecer X-Api-Key no header']));
            return $response->withHeader('Content-type', 'application\json')->withStatus(403);
        } else if (!in_array($apiKey[0],$validApiKeys)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['message' => 'X-Api-Key inválida']));
            return $response->withHeader('Content-type', 'application\json')->withStatus(401);
        }
        
        return $handler->handle($request);
    }
}