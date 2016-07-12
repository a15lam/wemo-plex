<?php

namespace a15lam\WemoPlex;

/**
 * Class Watcher
 *
 * Watches the Plex Media Server and controls Wemo device(s) as needed.
 *
 * @package a15lam\WemoPlex
 */
class Watcher
{
    /** @type \a15lam\WemoPlex\WemoClient */
    protected $wemo;
    /** @type \a15lam\WemoPlex\PlexClient */
    protected $plex;
    /** @type null An internal flag to keep track of last player */
    protected $lastPlayer = null;

    /**
     * Watcher constructor.
     */
    public function __construct()
    {
        $this->wemo = new WemoClient(Config::get('device_mapping'));
        $this->plex = new PlexClient([
            'host' => Config::get('host'),
            'port' => Config::get('port'),
            'api'  => Config::get('api')
        ]);
    }

    /**
     * Run it!
     */
    public function run()
    {
        $player = $this->plex->getPlayer();

        if (!empty($player)) {
            Logger::debug('Current player - ' . $player['title'] . ':' . $player['state']);
            $this->lastPlayer = $player;
            switch ($player['state']) {
                case 'playing':
                    $this->wemo->off($player['title']);
                    break;
                case 'paused':
                    $this->wemo->dim($player['title'], Config::get('dim_on_pause', 40));
                    break;
                default:
                    $this->wemo->on($player['title']);
                    break;
            }
        } elseif (!empty($this->lastPlayer)) {
            $this->wemo->on($this->lastPlayer['title']);
        }

        Logger::debug("Running... [" . $this->wemo->getStatus() . "]");
    }
}