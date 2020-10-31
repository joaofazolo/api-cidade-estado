<?php

namespace App\Application\Controllers;

use App\Domain\Cidade\Cidade;
use App\Persistence\CidadeRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CidadeController
{
    private $database;

    public function __construct(ContainerInterface $container)
    {
        $this->database = $container->get('settings')['database'];
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $cidadeRepository = new CidadeRepository($this->database);
        return $response;
    }

    public function insert(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $cidade = new Cidade(null, $data['nome'], $data['estado_id'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
        $cidadeRepository = new CidadeRepository($this->database);
        $cidade = $cidadeRepository->insert($cidade);

        $response->getBody()->write(json_encode($cidade));

        return $response->withHeader('Content-type', 'application\json');
        
    }
}