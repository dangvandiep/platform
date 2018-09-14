<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 10:44
 */

namespace DangVanDiep\Platform\Contracts;

interface Factory
{
    /**
     * Get a platform connection instance.
     *
     * @param string $driver
     * @param string $appkey
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function connection($driver = null, $appkey = null);

    /**
     * Set the default connection.
     *
     * @param string $driver
     * @param string $appkey
     *
     * @return void
     */
    public function setConnection($driver, $appkey);
}
