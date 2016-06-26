<?php

namespace a15lam\Exceptions;

class PlexException extends InternalException
{
    public function __construct($msg)
    {
        $msg = '[PlexException]' . $msg;
        return parent::__construct($msg);
    }
}