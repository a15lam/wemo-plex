<?php
/**
 * Created by PhpStorm.
 * User: arif
 * Date: 8/28/16
 * Time: 2:39 PM
 */

namespace a15lam\WemoPlex;

class Workspace extends \a15lam\Workspace\Workspace
{
    protected static $configInfo = __DIR__ . '/../config.php';

    protected static $logPath = __DIR__ . '/../storage/logs/';
}