<?php

namespace App\Domain;

use JsonSerializable;

class Cidade implements JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $estadoId;

    /**
     * @var string
     */
    private $dataCriacao;

    /**
     * @var string
     */
    private $dataAtualizacao;

    /**
     * @param string|null  $id
     * @param string    $nome
     * @param string   $estadoId
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
