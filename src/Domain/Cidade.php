<?php

namespace App\Domain;

use JsonSerializable;
/**
 * @OA\Schema()
 */
class Cidade implements JsonSerializable
{
    /**
     * ID da cidade
     * @var string
     * * @OA\Property()
     */
    private $id;

    /**
     * Nome da cidade
     * @var string
     * @OA\Property()
     */
    private $nome;

    /**
     * ID do estado que a cidade pertence
     * @var string
     * @OA\Property()
     */
    private $estadoId;

    /**
     * Data de criação
     * @var string
     * @OA\Property()
     */
    private $dataCriacao;

    /**
     * Data de atualização
     * @var string
     * @OA\Property()
     */
    private $dataAtualizacao;

    /**
     * @param string|null  $id
     * @param string    $nome
     * @param string    $estadoId
     * @param string    $lastName
     * @param string    $dataCriacao
     * @param string    $dataAtualizacao
     * 
     */
    public function __construct(?string $id, string $nome, string $estadoId, string $dataCriacao, string $dataAtualizacao)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->estadoId = $estadoId;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;

    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return int
     */
    public function getEstadoId(): string
    {
        return $this->estadoId;
    }

    /**
     * @return string
     */
    public function getDataCriacao(): string
    {
        return $this->dataCriacao;
    }

    /**
     * @return string
     */
    public function getDataAtualizacao(): string
    {
        return $this->dataAtualizacao;
    }

    /**
     * @return array
     */
    public function jsonSerialize($excludeId = false)
    {
        $array = [
            'id' => $this->id,
            'nome' => $this->nome,
            'estadoId' => $this->estadoId,
            'dataCriacao' => $this->dataCriacao,
            'dataAtualizacao' => $this->dataAtualizacao,
        ];

        if ($excludeId) {
            unset($array['id']);
        }

        return $array;
    }
}
