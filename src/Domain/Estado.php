<?php

namespace App\Domain;

use JsonSerializable;
/**
 * @OA\Schema()
 */
class Estado implements JsonSerializable
{
    /**
     * O ID do estado
     * @var string
     * @OA\Property()
     */
    private $id;

    /**
     * O nome do estado
     * @var string
     * @OA\Property()
     */
    private $nome;

    /**
     * A abreviação do estado
     * @var string
     * @OA\Property()
     */
    private $abreviacao;

    /**
     * A data de criação do estado
     * @var string
     * @OA\Property()
     */
    private $dataCriacao;

    /**
     * A data de atualização do estado
     * @var string
     * @OA\Property()
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
