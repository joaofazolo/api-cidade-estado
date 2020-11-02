<?php

namespace App\Application\Controllers;

use App\Domain\Cidade;
use App\Exception\EstadoNotFoundException;
use App\Persistence\CidadeRepository;
use App\Persistence\EstadoRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Exceptions\ValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Respect\Validation\Validator as validator;

class CidadeController
{
    private $repository;
    private $estadoRepository;

    public function __construct(CidadeRepository $repository, EstadoRepository $estadoRepository)
    {
        $this->repository = $repository;
        $this->estadoRepository = $estadoRepository;
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $limit = isset($request->getQueryParams()['limit']) ? (int)$request->getQueryParams()['limit'] : null;
        $offset = isset($request->getQueryParams()['offset']) ? (int)$request->getQueryParams()['offset'] : null;
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : null;

        $cidades = $this->repository->findAll($search, $limit, $offset);

        $response->getBody()->write(json_encode($cidades));

        return $response->withHeader('Content-type', 'application\json');
    }

    public function insert(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        try {
            $this->validateInsert($data);

            $this->estadoRepository->findById($data['estado_id']);

            $cidade = new Cidade(null, $data['nome'], $data['estado_id'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

            $cidade = $this->repository->insert($cidade);
        } catch (ValidationException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(422);
        } catch (EstadoNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($cidade));

        return $response->withHeader('Content-type', 'application\json');
        
    }

    public function show(Request $request, Response $response, $args)
    {
        try {
            $cidade = $this->repository->findById($args['id']);
        } catch (CidadeNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($cidade));

        return $response->withHeader('Content-type', 'application\json');
    }

    public function update(Request $request, Response $response, $args)
    {
        try {
            $this->validateInsert($request->getParsedBody());
            
            $this->repository->update($args['id'], $request->getParsedBody());

            $response->getBody()->write(json_encode(['message' => 'Cidade atualizada com sucesso']));
        } catch (CidadeNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            
            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        } catch (ValidationException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(422);
        }

        return $response->withHeader('Content-type', 'application\json');
    }

    public function delete(Request $request, Response $response, $args)
    {
        try {
            $this->repository->delete($args['id']);
        } catch (CidadeNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        }
        $response->getBody()->write(json_encode(['message' => 'Cidade deletada com sucesso']));

        return $response->withHeader('Content-type', 'application\json');
    }

    private function validateInsert($data)
    {
        validator::key('nome')->check($data);

        validator::key('estado_id')->check($data);
    }
}