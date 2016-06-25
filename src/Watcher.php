<?php
/**
 * Created by PhpStorm.
 * User: arif
 * Date: 6/25/16
 * Time: 1:07 AM
 */

namespace a15lam\WemoPlex;

class Watcher
{
    /** @type \a15lam\WemoPlex\WemoClient  */
    protected $wemo;
    /** @type \a15lam\WemoPlex\PlexClient  */
    protected $plex;
    
    protected $lastPlayer = null;

    public function __construct()
    {
        $this->wemo = new WemoClient(Config::get('device_mapping'));
        $this->plex = new PlexClient([
            'host' => Config::get('host'),
            'port' => Config::get('port'),
            'api'  => Config::get('api')
        ]);
    }

    public function run()
    {
        $player = $this->plex->getPlayer();
        
        if(!empty($player)) {
            $this->lastPlayer = $player;
            switch ($player['state']) {
                case 'playing':
                    $this->wemo->off($player['title']);
                    break;
                case 'paused':
                    $this->wemo->on($player['title']);
                    break;
                default:
                    $this->wemo->on($player['title']);
                    break;
            }
        } elseif(!empty($this->lastPlayer)) {
            $this->wemo->on($this->lastPlayer['title']);
        }

        echo "Running... [" . $this->wemo->getStatus() . "]" . PHP_EOL;
    }
}