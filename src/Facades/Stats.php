<?php

namespace Nauticsoft\LaravelStats\Facades;

use Illuminate\Support\Facades\Facade;
use Nauticsoft\LaravelStats\Stats as StatsClass;

class Stats extends Facade
{
    public static function getFacadeAccessor()
    {
        return StatsClass::class;
    }
}
