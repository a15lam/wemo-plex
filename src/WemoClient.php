<?php

namespace a15lam\WemoPlex;

use a15lam\Exceptions\WemoException;
use a15lam\PhpWemo\Discovery;
use a15lam\WemoPlex\Contracts\DeviceInterface;
use a15lam\PhpWemo\Contracts\DeviceInterface as WemoInterface;

/**
 * Class WemoClient
 *
 * Wemo device(s) client.
 *
 * @package a15lam\WemoPlex
 */
class WemoClient implements DeviceInterface
{
    /*********************************
     * Enumerations
     *********************************/
    const PLAYED  = 'played';
    const STOPPED = 'stopped';

    /**
     * Media player (tv, phone, etc.) to Wemo device mapping
     *
     * @type array
     */
    protected $mapping = [];
    /**
     * A runtime flag to indicate if any media is being playing on a player.
     *
     * @type bool
     */
    protected $played = false;

    /**
     * WemoClient constructor.
     *
     * @param array $mapping
     *
     * @throws WemoException
     */
    public function __construct(array $mapping)
    {
        foreach ($mapping as $key => $map) {
            if (!isset($map['player'])) {
                throw new WemoException("No 'player' set in device mapping");
            } elseif (!isset($map['wemo'])) {
                throw new WemoException("No 'wemo' set in device mapping");
            }

            $wemoInstance = [];
            if (is_array($map['wemo'])) {
                foreach ($map['wemo'] as $wemo) {
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

    /**
     * Turn on wemo device(s) for a player.
     *
     * @param string $player
     *
     * @return bool
     * @throws \a15lam\Exceptions\WemoException
     */
    public function on($player)
    {
        if ($this->played === true) {
            $wemo = $this->getMapByPlayer($player);

            Logger::info('Lights turning on. Hope you enjoyed the movie.');
            /** @type WemoInterface $device */
            foreach ($wemo as $device) {
                if ($device->isDimmable()) {
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

    /**
     * Turn off wemo device(s) for a player.
     *
     * @param string $player
     *
     * @return bool
     * @throws \a15lam\Exceptions\WemoException
     */
    public function off($player)
    {
        if ($this->played === false) {
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

    /**
     * Dim (dimmable) wemo device(s) for a player
     *
     * @param string $player
     * @param int    $percent Dim level (0-100%)
     *
     * @return bool
     * @throws \a15lam\Exceptions\WemoException
     */
    public function dim($player, $percent = 40)
    {
        if ($this->played === true) {
            $wemo = $this->getMapByPlayer($player);

            Logger::info('Lights turning on. Movie paused, take a quick break!');
            /** @type WemoInterface $device */
            foreach ($wemo as $device) {
                if ($device->isDimmable()) {
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

    /**
     * Returns 'played' if any media is being played and lights turned off
     * or else returns 'stopped'.
     *
     * @return string
     */
    public function getStatus()
    {
        if ($this->played === true) {
            return static::PLAYED;
        } else {
            return static::STOPPED;
        }
    }

    /**
     * Retuns wemo devices mapped to a player.
     *
     * @param string $player
     *
     * @return string|array
     * @throws \a15lam\Exceptions\WemoException
     */
    private function getMapByPlayer($player)
    {
        foreach ($this->mapping as $map) {
            if ($map['player'] === $player) {
                return $map['wemo'];
            }
        }

        throw new WemoException("No wemo mapping found for player $player");
    }
}