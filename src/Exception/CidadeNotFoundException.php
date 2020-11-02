<?php
namespace App\Exception;

class CidadeNotFoundException extends Exception
{
    public $message = 'Cidade não encontrada';
}