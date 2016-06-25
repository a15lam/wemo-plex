<?php

namespace a15lam\WemoPlex;

use a15lam\WemoPlex\Contracts\PlexInterface;
use a15lam\Exceptions\PlexException;

class PlexClient implements PlexInterface
{
    const PORT = '32400';
    const API = 'status/sessions';
    
    protected $url;
    
    public function __construct($config)
    {
        $host = $config;
        $port = static::PORT;
        $api = static::API;
        
        if(is_array($config)){
            if(isset($config['host'])){
                $host = $config['host'];
            } else {
                throw new PlexException("PlexClient config is missing 'host'");
            }
            
            $port = (isset($config['port']))? $config['port'] : static::PORT;
            $api = (isset($config['api']))? $config['api'] : static::API;
        }
        
        $this->url = 'http://' . $host . ':' . $port . '/' . $api;
    }

    public function getStatus()
    {
        try {
            $options = [
                CURLOPT_URL            => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_VERBOSE        => false,
                CURLOPT_HTTPHEADER     => [
                    'Accept:application/json'
                ]
            ];

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            $response = json_decode($response, true);
            return $response;
        } catch (\Exception $e) {
            throw new PlexException("Failed to make curl request ". $e->getMessage());
        }
    }

    public function getPlayer()
    {
        $status = $this->getStatus();
        $info = $status['_children'];
        
        if(!empty($info)) {
            $video = [];
            foreach ($info as $inf) {
                if (isset($inf['_elementType']) && $inf['_elementType'] === 'Video') {
                    $video = $inf['_children'];
                    break;
                }
            }

            foreach ($video as $v) {
                if (isset($v['_elementType']) && $v['_elementType'] === 'Player') {
                    return $v;
                }
            }
        }
        
        Logger::debug("No status returned from Plex server. Probably no media is playing.");
        
        return null;
    }
}