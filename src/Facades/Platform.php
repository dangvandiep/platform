<?php

namespace DangVanDiep\Platform\Facades;

use DangVanDiep\Platform\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

/**
 * Class Platform
 *
 * @package DangVanDiep\Platform\Facades
 *
 * @see     \DangVanDiep\Platform\Contracts\Driver
 *
 * @mixin \DangVanDiep\Platform\Contracts\Factory
 *
 */
class Platform extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
