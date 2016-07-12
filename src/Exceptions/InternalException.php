<?php

namespace a15lam\Exceptions;

use a15lam\WemoPlex\Logger;

/**
 * Class InternalException
 *
 * @package a15lam\Exceptions
 */
class InternalException extends \Exception
{
    /**
     * InternalException constructor.
     *
     * @param string $msg
     */
    public function __construct($msg)
    {
        Logger::error($msg);

        return parent::__construct($msg, 500);
    }
}