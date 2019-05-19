<?php declare(strict_types=1);

namespace App\Core;

use App\Core\Drivers\JsonDriver;

final class Config
{
    private static $configCache = [];

    public static function GetDatabaseDriver() : Driver
    {
        $driver = null;
        if (!isset(self::$configCache['db_driver'])) {
            self::$configCache['db_driver'] = new JsonDriver();
        }
        $driver = self::$configCache['db_driver'];

        return $driver;
    }
}