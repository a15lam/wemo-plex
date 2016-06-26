# wemo-plex
This is a PHP daemon service that automatically turns off your light 
(Wemo device) when you play something from your Plex Media Server, 
then turns on your light when media is paused or stopped. 

## Installation
You need to have composer installed for installing and running this software. 
See https://getcomposer.org.

### Linux
    cd /opt
    git clone https://github.com/a15lam/wemo-plex.git
    cd wemo-plex
    composer install
    
## Configuration
Edit the `config.php` file as needed. See the file for details. In the config 
you can easily map your Wemo devices to a Player/Client/TV that plays from your 
Plex Media Server. For example: 

    'device_mapping' => [
        [
            'player' => 'TV UN46C8000_USA',     // Plex device title as it shows on Plex device page.
            'wemo'   => 'media room'            // Wemo device name as it shows on your Wemo mobile app.
        ],
        [
            'player' => 'TV UN55F6300',         // Example: Supports multiple player-device mapping.
            'wemo'   => 'living room'
        ],
        [
            'player' => 'Plex Web (Chrome)',
            'wemo'   => ['light 1', 'light 2']  // Example: Supports multiple Wemo devices for a Plex player.
        ]
     ]

## Run

This is a daemon service, therefore, it needs to run continuously. You can do a 
quick test by running in your linux shell using - `php daemon.php` from within your installation 
directory. This will hold up your shell while the program is running. Press `ctrl + c` to stop it.
You can also run this in the background using - `php daemon.php &`. Press `ctrl + z` to stop background process. 

The best way to run this is to run it as system service. You can do this 
easily on a Ubuntu/Debian based system. Just copy `wemo-plex.conf` to `/etc/init/` directory. 

    sudo cp wemo-plex.conf /etc/init/wemo-plex.conf

Then edit `wemo-plex.conf` to make that the path on line 16 is correct. 

    sudo vim /etc/init/wemo-plex.conf

After this you can start and stop the service like below. 

    sudo service wemo-plex start
    sudo service wemo-plex stop
    
    // To check service status
    sudo service wemo-plex status
    
    // To restart service
    sudo service wemo-plex restart
    
Your service will also start automatically when your system boots up. Once the service 
is running, play any media from your Plex Media Server and watch your lights turn off and on 
as you play, pause and stop. 

## Logging
By default everything is logged at /path/to/your/install/storage/logs/main.log. 
You can change the log path from `config.php`. You can also change the log level 
from the config page. Default log level is `INFO`. Set this to `DEBUG` for better 
visibility and troubleshooting. 

Note: Leaving log level to `DEBUG` is not recommended. This will quickly make your log file huge.
