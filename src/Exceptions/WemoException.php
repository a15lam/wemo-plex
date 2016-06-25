<?php

namespace a15lam\Exceptions;


class WemoException extends InternalException
{
    public function __construct($msg)
    {
        $msg = '[WemoException]' . $msg;
        return parent::__construct($msg);
    }
}