<?php
require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set(\a15lam\WemoPlex\Workspace::config()->get('timezone'));
$_int = 2; //2 seconds
$watcher = new \a15lam\WemoPlex\Watcher();

while(true){
    if(\a15lam\WemoPlex\Workspace::config()->get('on_time', false)){
        $startTime = strtotime(\a15lam\WemoPlex\Workspace::config()->get('start_time', '06:00 PM'));
        $endTime = strtotime(\a15lam\WemoPlex\Workspace::config()->get('end_time', '06:00 AM NEXT DAY'));

        if(time() >= $startTime && time() <= $endTime){
            $watcher->run();
        }
    } else {
        $watcher->run();
    }
    sleep($_int);
}