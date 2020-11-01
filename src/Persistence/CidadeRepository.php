<?php

namespace App\Persistence;

use App\Domain\Cidade\Cidade;
use App\Domain\User\UserNotFoundException;
use stdClass;

class CidadeRepository extends Repository
{
    /**
     * {@inheritdoc}
     */
    public function findAll($limit, $offset)
    {
        $collection = $this->client->cidades;

        $cursor = $collection->find([], ['limit' => $limit, 'skip' => $offset]);

        $cidades = [];

        foreach ($cursor as $cidadeDocumento) {

            $cidade = new stdClass();

            $cidade->id = (string)$cidadeDocumento['_id'];

            $cidade->name = $cidadeDocumento['nome'];

            $cidade->estadoId = $cidadeDocumento['estadoId'];

            $cidade->dataCriacao = $cidadeDocumento['dataCriacao'];

            $cidade->dataAtualizacao = $cidadeDocumento['dataAtualizacao'];

            $cidades[] = $cidade;
        }
        
        return $cidades;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): Cidade
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }

    public function insert(Cidade $cidade)
    {
        $collection = $this->client->cidades;

        $insertOneResult = $collection->insertOne($cidade->jsonSerialize());

        $cidade->setId($insertOneResult->getInsertedId());

        return $cidade;
    }
}
