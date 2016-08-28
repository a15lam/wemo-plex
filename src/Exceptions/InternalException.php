<?php

namespace a15lam\Exceptions;

use a15lam\WemoPlex\Workspace as WS;

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
        WS::log()->error($msg);

        return parent::__construct($msg, 500);
    }
}