<?php

namespace App\Domain;

use JsonSerializable;

class Estado implements JsonSerializable
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
    private $abreviacao;

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
     * @param string       $nome
     * @param string       $abreviacao
     * @param string|null  $dataCriacao
     * @param string|null  $dataAtualizacao
     * 
     */
    public function __construct(?string $id, string $nome, string $abreviacao, ?string $dataCriacao, ?string $dataAtualizacao)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->abreviacao = $abreviacao;
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
     * @return string
     */
    public function getAbreviacao(): string
    {
        return $this->abreviacao;
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
            'abreviacao' => $this->abreviacao,
            'dataCriacao' => $this->dataCriacao,
            'dataAtualizacao' => $this->dataAtualizacao,
        ];

        if ($excludeId) {
            unset($array['id']);
        }

        return $array;
    }
}
