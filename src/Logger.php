<?php

namespace a15lam\WemoPlex;

/**
 * Class Logger
 *
 * @package a15lam\WemoPlex
 */
class Logger
{
    /*********************************
     * Enumerations
     *********************************/

    const ERROR   = 4;
    const WARNING = 3;
    const INFO    = 2;
    const DEBUG   = 1;
    const FILE    = 'main.log';

    /**
     * Set this to true to completely silence the logger
     *
     * @type bool
     */
    public static $silent = false;

    /**
     * Writes to log file.
     *
     * @param int    $level
     * @param string $msg
     *
     * @return bool
     * @throws \Exception
     */
    protected static function write($level, $msg)
    {
        if (static::$silent) {
            return false;
        }

        if (!static::isAllowed($level)) {
            return false;
        }

        date_default_timezone_set(Config::get('timezone'));

        $time = date('Y-m-d H:i:s', time());

        $msg = "[" . $time . "][" . static::getLevelName($level) . "] " . $msg . PHP_EOL;
        $file = rtrim(Config::get('log_path'), '/') . '/' . static::FILE;
        $fh = fopen($file, 'a');

        if (!fwrite($fh, $msg)) {
            throw new \Exception('Failed to write to log file at ' . $file);
        }

        return true;
    }

    /**
     * Checks to see if log level is allowed by config.
     *
     * @param int $level
     *
     * @return bool
     */
    protected static function isAllowed($level)
    {
        if ($level >= Config::get('log_level', 0)) {
            return true;
        }

        return false;
    }

    /**
     * Gets the log level name by value
     *
     * @param int $value
     *
     * @return null|string
     */
    protected static function getLevelName($value)
    {
        $map = array_flip((new \ReflectionClass(self::class))->getConstants());

        return (array_key_exists($value, $map) ? $map[$value] : null);
    }

    /**
     * Logs warning messages.
     *
     * @param string $msg
     *
     * @return bool
     * @throws \Exception
     */
    public static function warn($msg)
    {
        return static::write(static::WARNING, $msg);
    }

    /**
     * Logs error messages.
     *
     * @param string $msg
     *
     * @return bool
     * @throws \Exception
     */
    public static function error($msg)
    {
        return static::write(static::ERROR, $msg);
    }

    /**
     * Logs info messages.
     *
     * @param string $msg
     *
     * @return bool
     * @throws \Exception
     */
    public static function info($msg)
    {
        return static::write(static::INFO, $msg);
    }

    /**
     * Logs debug messages.
     *
     * @param string $msg
     *
     * @return bool
     * @throws \Exception
     */
    public static function debug($msg)
    {
        return static::write(static::DEBUG, $msg);
    }
}