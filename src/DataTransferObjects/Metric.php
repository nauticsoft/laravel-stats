<?php

namespace Nauticsoft\LaravelStats\DataTransferObjects;

use Illuminate\Support\Carbon;

class Metric
{
    public function __construct(
        public Carbon $date,
        public string $key,
        public int $value,
    ) {
        //
    }
}
