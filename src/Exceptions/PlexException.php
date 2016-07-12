<?php

namespace a15lam\Exceptions;

/**
 * Class PlexException
 *
 * @package a15lam\Exceptions
 */
class PlexException extends InternalException
{
    /**
     * PlexException constructor.
     *
     * @param string $msg
     */
    public function __construct($msg)
    {
        $msg = '[PlexException]' . $msg;

        return parent::__construct($msg);
    }
}