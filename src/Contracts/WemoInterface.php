<?php
/**
 * Created by PhpStorm.
 * User: arif
 * Date: 6/25/16
 * Time: 12:57 AM
 */

namespace a15lam\WemoPlex\Contracts;

interface WemoInterface
{
    public function on($player);
    
    public function off($player);
}