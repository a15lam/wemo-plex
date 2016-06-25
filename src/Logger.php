<?php

namespace a15lam\WemoPlex;

class Logger
{
    const ERROR = 4;
    const WARNING = 3;
    const INFO = 2;
    const DEBUG = 1;
    const FILE = 'main.log';
    
    public static $silent = false;
    
    protected static function write($level, $msg)
    {
        if(static::$silent){
            return false;
        }
        
        if(!static::isAllowed($level)){
            return false;
        }
        
        date_default_timezone_set(Config::get('timezone'));
        
        $time = date('Y-m-d H:i:s', time());

        $msg = "[" . $time . "][" . static::getLevelName($level) . "] " . $msg . PHP_EOL;
        $file = rtrim(Config::get('log_path'), '/') . '/' . static::FILE;
        $fh = fopen($file, 'a');
        
        if(!fwrite($fh, $msg)){
            throw new \Exception('Failed to write to log file at ' . $file);
        }
        
        return true;
    }
    
    protected static function isAllowed($level)
    {
        if($level >= Config::get('log_level', 0)){
            return true;
        }
        
        return false;
    }

    protected static function getLevelName($value)
    {
        $map = array_flip((new \ReflectionClass(self::class))->getConstants());
        return (array_key_exists($value, $map) ? $map[$value] : null);
    }
    
    public static function warn($msg)
    {
        return static::write(static::WARNING, $msg);
    }
    
    public static function error($msg)
    {
        return static::write(static::ERROR, $msg);
    }
    
    public static function info($msg)
    {
        return static::write(static::INFO, $msg);
    }
    
    public static function debug($msg)
    {
        return static::write(static::DEBUG, $msg);
    }
}