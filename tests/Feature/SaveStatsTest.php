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
        ->and($row->value)->toBe(12)
        ->and($row->created_at)->toBe(now()->format('Y-m-d H:i:s'))
        ->and($row->updated_at)->toBe(now()->format('Y-m-d H:i:s'));
});

it('updates stats', function () {
    $date = now();
    Stats::type('request')->save($date, 'count', 12);
    $this->travelTo(now()->addDay());
    Stats::type('request')->save($date, 'count', 2);

    $row = DB::table('stats')->first();
    expect(DB::table('stats')->count())->toBe(1)
        ->and($row->timestamp)->toBe($date->timestamp)
        ->and($row->type)->toBe('request')
        ->and($row->key)->toBe('count')
        ->and($row->value)->toBe(2)
        ->and($row->created_at)->toBe($date->format('Y-m-d H:i:s'))
        ->and($row->updated_at)->toBe(now()->format('Y-m-d H:i:s'));
});

it('cannot create stats without a type', function () {
    Stats::save(now(), 'count', 12);
})->throws(UnexpectedValueException::class);
