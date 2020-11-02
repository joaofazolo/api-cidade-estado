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

    public function __construct(EstadoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/estado",
     *     tags={"Estado"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="query", name="limit", @OA\Schema(type="integer"), description="Limite quantidade"),
     *     @OA\Parameter(in="query", name="sortField", @OA\Schema(type="string"), description="Atributo para ordenar"),
     *     @OA\Parameter(in="query", name="sortType", @OA\Schema(type="string"), description="Tipo da ordenação (ASC ou DESC)"),
     *     @OA\Parameter(in="query", name="offset", @OA\Schema(type="integer"), description="Offset"),
     *     @OA\Parameter(in="query", name="search", @OA\Schema(type="string"), description="String de busca no nome e descrição"),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(@OA\Items(ref="#/components/schemas/Estado")),
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
    public function index(Request $request, Response $response)
    {
        $limit = isset($request->getQueryParams()['limit']) ? (int)$request->getQueryParams()['limit'] : null;
        $offset = isset($request->getQueryParams()['offset']) ? (int)$request->getQueryParams()['offset'] : null;
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : null;
        $sortField = isset($request->getQueryParams()['sortField']) ? $request->getQueryParams()['sortField'] : null;
        $sortType = isset($request->getQueryParams()['sortType']) ? $request->getQueryParams()['sortType'] : null;

        $estados = $this->repository->findAll($search ,$limit, $offset, $sortField, $sortType);

        $response->getBody()->write(json_encode($estados));

        return $response->withHeader('Content-type', 'application\json');
    }

    /**
     * @OA\Post(
     *     path="/estado",
     *     tags={"Estado"},
     *     security={{"apiKey":{}}},
     *     @OA\RequestBody(
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="nome",
     *                  type="string",
     *                  description="O Nome do estado"
     *              ),
     *              @OA\Property(property="abreviacao",
     *                  type="string",
     *                  description="A abreviação do estado"
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Estado"),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Falha de validação"
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/estado/{estadoId}",
     *     tags={"Estado"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="path", name="estadoId", @OA\Schema(type="string"), description="Id do estado", required=true),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Estado"),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Estado não encontrado"
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/estado/{estadoId}",
     *     tags={"Estado"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="path", name="estadoId", @OA\Schema(type="string"), description="Id do estado", required=true),
     *     @OA\Response(
     *          response=204,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Estado não encontrado"
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
    public function delete(Request $request, Response $response, $args)
    {
        try {
            $this->repository->delete($args['id']);
        } catch (EstadoNotFoundException $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));

            return $response->withHeader('Content-type', 'application\json')->withStatus(404);
        }
        $response->getBody()->write(json_encode(['message' => 'Estado deletado com sucesso']));

        return $response->withHeader('Content-type', 'application\json')->withStatus(204);
    }

    /**
     * @OA\Put(
     *     path="/estado/{estadoId}",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="path", name="estadoId", @OA\Schema(type="string"), description="Id do estado", required=true),
     *     tags={"Estado"},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Estado não encontrado"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Falha de validação"
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
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