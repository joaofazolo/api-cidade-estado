<?php

namespace App\Persistence;

use App\Domain\Cidade;
use App\Exception\CidadeNotFoundException;

class CidadeRepository extends Repository
{
    public function findAll($search, $limit, $offset)
    {
        $collection = $this->client->cidades;

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

        $cursor = $collection->find($searchArray, $options);

        $cidades = [];

        foreach ($cursor as $cidadeDocumento) {
            $cidade = new Cidade(
                (string)$cidadeDocumento['_id'],
                $cidadeDocumento['nome'],
                $cidadeDocumento['estadoId'],
                $cidadeDocumento['dataCriacao'],
                $cidadeDocumento['dataAtualizacao']
            );

            $cidades[] = $cidade;
        }
        
        return $cidades;
    }

    public function findById(string $id): Cidade
    {
        $collection = $this->client->cidades;

        $documento = $collection->findOne(['_id' =>  new \MongoDB\BSON\ObjectId($id)]);

        if (is_null($documento)) {
            throw new CidadeNotFoundException();
        }

        $cidade = new Cidade((string)$documento->_id, $documento['nome'], $documento['estadoId'], $documento['dataCriacao'], $documento['dataAtualizacao']);

        return $cidade;
    }

    public function insert(Cidade $cidade): Cidade
    {
        $collection = $this->client->cidades;

        $insertOneResult = $collection->insertOne($cidade->jsonSerialize(true));

        $cidade->setId((string)$insertOneResult->getInsertedId());

        return $cidade;
    }

    public function update($id, $attrs): bool
    {
        $collection = $this->client->cidades;

        $updateResult = $collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'nome' => $attrs['nome'],
                'estadoId' => $attrs['estadoId'],
                'dataAtualizacao' => date('Y-m-d H:i:s')
            ]]
        );

        if ($updateResult->getMatchedCount() < 1) {
            throw new CidadeNotFoundException('Cidade não encontrado');
        }

        return true;
    }

    public function delete($id)
    {
        $collection = $this->client->cidades;

        $deleteResult = $collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

        if($deleteResult->getDeletedCount() < 1) {
            throw new CidadeNotFoundException('Cidade não encontrado');
        }

        return true;
    }
}
