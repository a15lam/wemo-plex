<?php

namespace a15lam\Exceptions;

use a15lam\WemoPlex\Logger;

class InternalException extends \Exception
{
    public function __construct($msg)
    {
        Logger::error($msg);
        return parent::__construct($msg, 500);
    }
}