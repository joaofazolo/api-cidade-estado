<?php
namespace App\Exception;

use Exception;

class CidadeNotFoundException extends Exception
{
    public $message = 'Cidade não encontrada';
}