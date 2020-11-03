<?php

namespace App\Application\Controllers;

use App\Domain\Cidade;
use App\Exception\CidadeNotFoundException;
use App\Exception\EstadoNotFoundException;
use App\Persistence\CidadeRepository;
use App\Persistence\EstadoRepository;
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

    /**
     * @OA\Get(
     *     path="/cidade",
     *     tags={"Cidade"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="query", name="limit", @OA\Schema(type="integer"), description="Limite quantidade"),
     *     @OA\Parameter(in="query", name="sortField", @OA\Schema(type="string"), description="Atributo para ordenar"),
     *     @OA\Parameter(in="query", name="sortType", @OA\Schema(type="string"), description="Tipo da ordenação (ASC ou DESC)"),
     *     @OA\Parameter(in="query", name="offset", @OA\Schema(type="integer"), description="Offset"),
     *     @OA\Parameter(in="query", name="search", @OA\Schema(type="string"), description="String de busca no nome"),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(@OA\Items(ref="#/components/schemas/Cidade")),
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $limit = isset($request->getQueryParams()['limit']) ? (int)$request->getQueryParams()['limit'] : null;
        $offset = isset($request->getQueryParams()['offset']) ? (int)$request->getQueryParams()['offset'] : null;
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : null;

        $cidades = $this->repository->findAll($search, $limit, $offset);

        $response->getBody()->write(json_encode($cidades));

        return $response->withHeader('Content-type', 'application\json');
    }

    /**
     * @OA\Post(
     *     path="/cidade",
     *     tags={"Cidade"},
     *     security={{"apiKey":{}}},
     *     @OA\RequestBody(
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="nome",
     *                  type="string",
     *                  description="O Nome da cidade"
     *              ),
     *              @OA\Property(property="estadoId",
     *                  type="string",
     *                  description="ID do estado"
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Cidade"),
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

    /**
     * @OA\Get(
     *     path="/cidade/{cidadeId}",
     *     tags={"Cidade"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="path", name="cidadeId", @OA\Schema(type="string"), description="Id da cidade", required=true),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Cidade"),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cidade não encontrada"
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/cidade/{cidadeId}",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="path", name="cidadeId", @OA\Schema(type="string"), description="Id da cidade", required=true),
     *     tags={"Cidade"},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cidade não encontrada"
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

    /**
     * @OA\Delete(
     *     path="/cidade/{cidadeId}",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(in="path", name="cidadeId", @OA\Schema(type="string"), description="Id da cidade", required=true),
     *     tags={"Cidade"},
     *     @OA\Response(
     *          response=204,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cidade não encontrada"
     *      ),
     *      @OA\Response(response=403, ref="#/components/responses/403"),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
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