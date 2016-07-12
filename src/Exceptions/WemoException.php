<?php

namespace a15lam\Exceptions;

/**
 * Class WemoException
 *
 * @package a15lam\Exceptions
 */
class WemoException extends InternalException
{
    /**
     * WemoException constructor.
     *
     * @param string $msg
     */
    public function __construct($msg)
    {
        $msg = '[WemoException]' . $msg;

        return parent::__construct($msg);
    }
}