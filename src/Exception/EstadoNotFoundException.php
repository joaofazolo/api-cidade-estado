<?php

namespace App\Exception;

use Exception;

class EstadoNotFoundException extends Exception
{
    public $message = 'Estado não encontrado';
}