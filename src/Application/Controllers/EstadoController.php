<?php

namespace App\Application\Controllers;

use App\Domain\Estado;
use App\Persistence\EstadoRepository;
use App\Exception\EstadoNotFoundException;
use Psr\Container\ContainerInterface;
use Respect\Validation\Exceptions\ValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Respect\Validation\Validator as validator;

class EstadoController
{
    private $repository;

    public function __construct(ContainerInterface $container)
    {
        $database = $container->get('settings')['database'];

        $this->repository = new EstadoRepository($database);
    }

    public function index(Request $request, Response $response)
    {
        $limit = isset($request->getQueryParams()['limit']) ? (int)$request->getQueryParams()['limit'] : null;
        $offset = isset($request->getQueryParams()['offset']) ? (int)$request->getQueryParams()['offset'] : null;
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : null;

        $estados = $this->repository->findAll($search ,$limit, $offset);

        $response->getBody()->write(json_encode($estados));

        return $response->withHeader('Content-type', 'application\json');
    }

    public function insert(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        try {
            $this->validateInsert($data);

            $estado = new Estado(null, $data['nome'], $data['abreviacao'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

            $estado = $this->repository->insert($estado);
        } catch (ValidationException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(422);
        }

        $response->getBody()->write(json_encode($estado));

        return $response->withHeader('Content-type', 'application\json');
    }

    public function show(Request $request, Response $response, $args)
    {
        try {
            $estado = $this->repository->findById($args['id']);
        } catch (EstadoNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($estado));

        return $response->withHeader('Content-type', 'application\json');
    }

    public function delete(Request $request, Response $response, $args)
    {
        try {
            $this->repository->delete($args['id']);
        } catch (EstadoNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        }
        $response->getBody()->write(json_encode(['message' => 'Estado deletado com sucesso']));

        return $response->withHeader('Content-type', 'application\json');
    }

    public function update(Request $request, Response $response, $args)
    {
        try {
            $this->validateInsert($request->getParsedBody());
            
            $this->repository->update($args['id'], $request->getParsedBody());

            $response->getBody()->write(json_encode(['message' => 'Estado atualizado com sucesso']));
        } catch (EstadoNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            
            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        } catch (ValidationException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(422);
        }

        return $response->withHeader('Content-type', 'application\json');
    }

    private function validateInsert($data)
    {
        validator::key('nome', validator::stringType())->check($data);

        validator::key('abreviacao', validator::stringType()->length(2, 2))->check($data);
    }
}