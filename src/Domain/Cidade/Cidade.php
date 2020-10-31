<?php

namespace App\Domain\Cidade;

use JsonSerializable;

class Cidade implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var integer
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
     * @param int|null  $id
     * @param string    $nome
     * @param integer   $estadoId
     * @param string    $lastName
     * @param string    $dataCriacao
     * @param string    $dataAtualizacao
     * 
     */
    public function __construct(?int $id, string $nome, int $estadoId, string $dataCriacao, string $dataAtualizacao)
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
     * @return int|null
     */
    public function getId(): ?int
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
    public function getEstadoId(): int
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
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'estadoId' => (int)$this->estadoId,
            'dataCriacao' => $this->dataCriacao,
            'dataAtualizacao' => $this->dataAtualizacao,
        ];
    }
}
