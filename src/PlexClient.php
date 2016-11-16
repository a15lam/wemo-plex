<?php

namespace a15lam\WemoPlex;

use a15lam\WemoPlex\Contracts\MediaInterface;
use a15lam\Exceptions\PlexException;
use a15lam\WemoPlex\Workspace as WS;
use a15lam\Workspace\Utility\ArrayFunc;
use a15lam\Workspace\Utility\DataFormat;

/**
 * Class PlexClient
 *
 * Plex Media Server (PMS) client.
 *
 * @package a15lam\WemoPlex
 */
class PlexClient implements MediaInterface
{
    /**
     * PMS port.
     */
    const PORT = '32400';
    /**
     * PMS API to use.
     */
    const API = 'status/sessions';
    /**
     * URL for the PMS
     *
     * @type string
     */
    protected $url;

    /**
     * PlexClient constructor.
     *
     * @param string|array $config
     *
     * @throws PlexException
     */
    public function __construct($config)
    {
        $host = $config;
        $port = static::PORT;
        $api = static::API;

        if (is_array($config)) {
            if (isset($config['host'])) {
                $host = $config['host'];
            } else {
                throw new PlexException("PlexClient config is missing 'host'");
            }

            $port = (isset($config['port'])) ? $config['port'] : static::PORT;
            $api = (isset($config['api'])) ? $config['api'] : static::API;
        }

        $this->url = 'http://' . $host . ':' . $port . '/' . $api;
    }

    /**
     * Gets PMS status
     *
     * @return array
     * @throws \a15lam\Exceptions\PlexException
     */
    public function getStatus()
    {
        try {
            $options = [
                CURLOPT_URL            => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_VERBOSE        => false
            ];

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            $response = DataFormat::xmlToArray($response, 1);

            return $response;
        } catch (\Exception $e) {
            throw new PlexException("Failed to make curl request " . $e->getMessage());
        }
    }

    /**
     * Gets player (client) device name.
     *
     * @return string|null
     * @throws \a15lam\Exceptions\PlexException
     */
    public function getPlayer()
    {
        $status = $this->getStatus();
        if(is_array($status)) {
            $info = (isset($status['MediaContainer']))?
                (isset($status['MediaContainer']['Video']))?
                    (isset($status['MediaContainer']['Video']['Player_attr']))?
                        $status['MediaContainer']['Video']['Player_attr'] : null : null : null;

            if (!empty($info)) {
                return $info;
            }
        }
        WS::log()->debug("No status returned from Plex server. Probably no media is playing.");

        return null;
    }
}