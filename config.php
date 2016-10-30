<?php
return [
    /*
     * Host name or IP address of your Plex Media Server (PMS).
     */
    'host'           => '192.168.1.145',
    /*
     * Port number of your PMS. (default 32400)
     */
    'port'           => '32400',
    /*
     * PMS status API (default status/sessions)
     */
    'api'            => 'status/sessions',
    /*
     * If you are using Wemo (dimmable) light bulb then set the 
     * light brightness (percentage) when media is paused. (default 40%)
     */
    'dim_on_pause'   => 40,
    /*
     * Set log level. Honors hierarchy of ERROR->WARNING->INFO->DEBUG
     */
    'log_level'      => \a15lam\Workspace\Utility\Logger::ERROR,
    /*
     * Set the directory where log file will be written. 
     */
    'log_path'       => __DIR__ . '/storage/logs/',
    /*
     * Set your timezone.
     */
    'timezone'       => 'America/New_York',
    /*
     * Set on_time to true to only control wemo devices
     * between the specified start_time and end_time
     */
    'on_time'        => false,
    'start_time'     => '06:00 PM',
    'end_time'       => '06:00 AM NEXT DAY',
    /*
     * Plex media player - to - Wemo device mapping.
     * Example:
     * 
     * 'device_mapping' => [
     *   [
     *       'player' => 'TV UN46C8000_USA',   // Plex device title as it shows on Plex device page.
     *       'wemo'   => 'media room'          // Wemo device name as it shows on your Wemo mobile app.
     *   ],
     *   [
     *       'player' => 'TV UN55F6300',       // Supports multiple player-device mapping.
     *       'wemo'   => 'living room'
     *   ],
     *   [
     *       'player' => 'Plex Web (Chrome)',
     *       'wemo'   => ['light 1', 'light 2']  // Supports multiple Wemo devices for a Plex player.
     *   ]
     * ]
     * 
     */
    'device_mapping' => [
        [
            'player' => 'TV UN46C8000_USA',
            'wemo'   => 'media room'
        ],
        [
            'player' => 'TV UN55F6300',
            'wemo'   => 'living room'
        ],
        [
            'player' => 'Plex Web (Chrome)',
            'wemo'   => ['arif', 'afrin']
        ]
    ]
];