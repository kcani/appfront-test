<?php

namespace App\Facades;

use App\Libs\ExchangeRateLib;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array get(string $from = null, string $to = null, bool $useCachedResult = true)
 */
class ExchangeRate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ExchangeRateLib::class;
    }
}
