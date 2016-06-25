<?php
require __DIR__ . '/vendor/autoload.php';

$_int = 3; //3 seconds
$watcher = new \a15lam\WemoPlex\Watcher();
\a15lam\WemoPlex\Logger::$silent = false;

while(true){
    $watcher->run();
    sleep($_int);
}