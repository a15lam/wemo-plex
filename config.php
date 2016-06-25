<?php
return [
    'host' => '192.168.1.145',
    'port' => '32400',
    'api'  => 'status/sessions',
    'log_level' => \a15lam\WemoPlex\Logger::INFO,
    'log_path' => __DIR__ . '/storage/logs/',
    'timezone' => 'America/New_York',
    'device_mapping' => [
        [
            'player' => 'TV UN46C8000_USA',
            'wemo' => 'media room'
        ],
        [
            'player' => 'TV UN55F6300',
            'wemo' => 'living room'
        ],
        [
            'player' => 'Plex Web (Chrome)',
            'wemo' => '1 Miya'
        ]
    ]
];