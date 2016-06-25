<?php
/**
 * Created by PhpStorm.
 * User: arif
 * Date: 6/25/16
 * Time: 12:47 AM
 */

namespace a15lam\WemoPlex;

use a15lam\PhpWemo\Discovery;
use a15lam\WemoPlex\Contracts\WemoInterface;

class WemoClient implements WemoInterface
{
    protected $mapping = [];
    protected $played = false;
    
    public function __construct(array $mapping)
    {
        foreach ($mapping as $key => $map){
            if(!isset($map['player'])){
                throw new \Exception("No 'player' set in device mapping");
            } elseif (!isset($map['wemo'])){
                throw new \Exception("No 'wemo' set in device mapping");
            }
            
            $wemoInstance = [];
            if(is_array($map['wemo'])){
                foreach ($map['wemo'] as $wemo){
                    $wemoInstance[] = Discovery::getDeviceByName($wemo);
                }
            } else {
                $wemoInstance[] = Discovery::getDeviceByName($map['wemo']);
            }
            
            $map['wemo'] = $wemoInstance;
            $mapping[$key] = $map;
        }
        
        $this->mapping = $mapping;
    }
    
    public function on($player)
    {
        if($this->played === true) {
            $wemo = $this->getMapByPlayer($player);

            foreach ($wemo as $device) {
                $device->On();
            }

            $this->played = false;
            return true;
        } else {
            return false;
        }
    }
    
    public function off($player)
    {
        $wemo = $this->getMapByPlayer($player);

        foreach ($wemo as $device){
            $device->Off();
        }
        
        $this->played = true;
        return true;
    }
    
    public function getStatus()
    {
        if($this->played === true){
            return  "played";
        } else {
            return  "stopped";
        }
    }
    
    private function getMapByPlayer($player)
    {
        foreach ($this->mapping as $map){
            if($map['player'] === $player){
                return $map['wemo'];
            }
        }
        
        throw new \Exception("No wemo mapping found for player $player");
    }
}