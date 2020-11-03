<?php

namespace App\Persistence;

use App\Domain\Estado;
use App\Exception\EstadoNotFoundException;

class EstadoRepository extends Repository
{
    public function findAll($search, $limit, $offset, $sortField, $sortType): array
    {
        $collection = $this->client->estados;

        $searchArray = [];
        
        if ($search) {
            $searchArray = [
                '$or' => [
                    ['nome' => new \MongoDB\BSON\Regex("^$search", 'i')],
                    ['abreviacao' => new \MongoDB\BSON\Regex("^$search", 'i')]
                ]
            ];
        }

        $options = [];

        if ($limit) {
            $options['limit'] = $limit;
        }

        if ($offset) {
            $options['skip'] = $offset;
        }

        if ($sortField) {

            if ($sortType == 'ASC') {
                $options['sort'][$sortField] = 1;
            }
            if ($sortType == 'DESC') {
                $options['sort'][$sortField] = -1;
            }
        }

        $cursor = $collection->find($searchArray, $options);

        $estados = [];

        foreach ($cursor as $estadoDocumento) {
            $estado = new Estado(
                (string)$estadoDocumento['_id'],
                $estadoDocumento['nome'],
                $estadoDocumento['abreviacao'],
                $estadoDocumento['dataCriacao'],
                $estadoDocumento['dataAtualizacao']
            );

            $estados[] = $estado;
        }
        
        return $estados;
    }

    public function findById(string $id): Estado
    {
        $collection = $this->client->estados;

        $documento = $collection->findOne(['_id' =>  new \MongoDB\BSON\ObjectId($id)]);

        if (is_null($documento)) {
            throw new EstadoNotFoundException();
        }

        $estado = new Estado((string)$documento->_id, $documento['nome'], $documento['abreviacao'], $documento['dataCriacao'], $documento['dataAtualizacao']);

        return $estado;
    }

    public function insert(Estado $estado) : Estado
    {
        $collection = $this->client->estados;

        $insertOneResult = $collection->insertOne($estado->jsonSerialize(true));

        $estado->setId((string)$insertOneResult->getInsertedId());

        return $estado;
    }

    public function update($id, $attrs): bool
    {
        $collection = $this->client->estados;

        $updateResult = $collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'nome' => $attrs['nome'],
                'abreviacao' => $attrs['abreviacao'],
                'dataAtualizacao' => date('Y-m-d H:i:s')
            ]]
        );

        if ($updateResult->getMatchedCount() < 1) {
            throw new EstadoNotFoundException();
        }

        return true;
    }

    public function delete($id)
    {
        $collection = $this->client->estados;

        $deleteResult = $collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

        if($deleteResult->getDeletedCount() < 1) {
            throw new EstadoNotFoundException();
        }

        return true;
    }
}
