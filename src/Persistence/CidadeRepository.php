<?php

namespace App\Persistence;

use App\Domain\Cidade\Cidade;
use App\Domain\User\UserNotFoundException;

class CidadeRepository extends Repository
{
    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
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
        $collection = $this->client->zoox->cidades;

        $insertOneResult = $collection->insertOne($cidade->jsonSerialize());

        $cidade->setId($insertOneResult->getInsertedId());

        return $cidade;
    }
}
