<?php

namespace a15lam\WemoPlex\Contracts;

/**
 * Interface DeviceInterface
 *
 * @package a15lam\WemoPlex\Contracts
 */
interface DeviceInterface
{
    public function on($player);
    
    public function off($player);
    
    public function dim($player, $percent);
}