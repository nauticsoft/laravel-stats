<?php

use Illuminate\Support\Facades\DB;
use Nauticsoft\LaravelStats\Facades\Stats;

it('creates stats', function () {
    Stats::type('request')->save(now(), 'count', 12);

    $row = DB::table('stats')->first();
    expect(DB::table('stats')->count())->toBe(1)
        ->and($row->timestamp)->toBe(now()->timestamp)
        ->and($row->type)->toBe('request')
        ->and($row->key)->toBe('count')
        ->and($row->value)->toBe(12);
});

it('updates stats', function () {
    $date = now();
    Stats::type('request')->save($date, 'count', 12);
    Stats::type('request')->save($date, 'count', 2);

    $row = DB::table('stats')->first();
    expect(DB::table('stats')->count())->toBe(1)
        ->and($row->timestamp)->toBe(now()->timestamp)
        ->and($row->type)->toBe('request')
        ->and($row->key)->toBe('count')
        ->and($row->value)->toBe(2);
});

it('cannot create stats without a type', function () {
    Stats::save(now(), 'count', 12);
})->throws(UnexpectedValueException::class);
