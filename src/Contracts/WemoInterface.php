<?php

namespace a15lam\WemoPlex\Contracts;

interface WemoInterface
{
    public function on($player);
    
    public function off($player);
}