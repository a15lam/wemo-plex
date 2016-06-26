<?php

namespace a15lam\WemoPlex;

use a15lam\Exceptions\WemoException;
use a15lam\PhpWemo\Contracts\DeviceInterface;
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
                throw new WemoException("No 'player' set in device mapping");
            } elseif (!isset($map['wemo'])){
                throw new WemoException("No 'wemo' set in device mapping");
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

            Logger::info('Lights turning on. Hope you enjoyed the movie.');
            /** @type DeviceInterface $device */
            foreach ($wemo as $device) {
                if($device->isDimmable()){
                    logger::debug('[on] Device dimmable. Setting to 100%.');
                    $device->dim(100);
                } else {
                    logger::debug('[on] Device not dimmable. Tunring on.');
                    $device->On();
                }
            }

            $this->played = false;
            return true;
        } else {
            return false;
        }
    }
    
    public function off($player)
    {
        if($this->played === false) {
            $wemo = $this->getMapByPlayer($player);

            Logger::info('Lights turning off. Enjoy your movie!');
            foreach ($wemo as $device) {
                $device->Off();
            }

            $this->played = true;

            return true;
        } else {
            return false;
        }
    }

    public function dim($player, $percent=40)
    {
        if($this->played === true){
            $wemo = $this->getMapByPlayer($player);

            Logger::info('Lights turning on. Movie paused, take a quick break!');
            /** @type DeviceInterface $device */
            foreach ($wemo as $device) {
                if($device->isDimmable()){
                    Logger::debug('[dim] Device dimmable. Dimming to ' . $percent . '%');
                    $device->dim($percent);
                } else {
                    Logger::debug('[dim] Device not dimmable. Turning on.');
                    $device->On();
                }
            }

            $this->played = false;
        } else {
            return false;
        }
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
        
        throw new WemoException("No wemo mapping found for player $player");
    }
}