<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 10:48
 */

namespace DangVanDiep\Platform;

use Composer\Autoload\ClassMapGenerator;
use DangVanDiep\Platform\Drivers\AbstractDriver;
use InvalidArgumentException;

class PlatformManager implements Contracts\Factory
{

    protected $driver;
    protected $appkey;

    /**
     * Get a platform connection instance.
     *
     * @param string $driver
     * @param string $appkey
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function connection($driver = null, $appkey = null)
    {
        $driver = $driver ?: $this->driver;
        $appkey = $appkey ?: $this->appkey;

        if (empty($driver) || empty($appkey)) {
            throw new InvalidArgumentException('No platform driver was specified.');
        }

        return new $driver($appkey);
    }

    /**
     * Set the default connection.
     *
     * @param string $driver
     * @param string $appkey
     *
     * @return void
     */
    public function setConnection($driver, $appkey)
    {
        $this->driver = $driver;
        $this->appkey = $appkey;
    }

    /**
     * Get list of suported driver
     *
     * @return array
     */
    public function getDrivers()
    {
        $drivers = collect(ClassMapGenerator::createMap(__DIR__ . '/Drivers'))
            ->except(AbstractDriver::class)
            ->keys()
            ->all();

        return $drivers;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
