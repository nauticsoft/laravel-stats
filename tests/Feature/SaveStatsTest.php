<?php

use Illuminate\Support\Facades\DB;
use Nauticsoft\LaravelStats\DataTransferObjects\Metric;
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

it('can save stats in bulk', function () {
    Stats::type('request')->bulkSave([
        new Metric(now(), 'count', 12),
        new Metric(now()->subDay(), 'count', 2),
    ]);

    [$first, $last] = DB::table('stats')->get();
    expect(DB::table('stats')->count())->toBe(2)
        ->and($first->timestamp)->toBe(now()->timestamp)
        ->and($first->type)->toBe('request')
        ->and($first->key)->toBe('count')
        ->and($first->value)->toBe(12)
        ->and($first->created_at)->toBe(now()->format('Y-m-d H:i:s'))
        ->and($first->updated_at)->toBe(now()->format('Y-m-d H:i:s'))
        ->and($last->timestamp)->toBe(now()->subDay()->timestamp)
        ->and($last->type)->toBe('request')
        ->and($last->key)->toBe('count')
        ->and($last->value)->toBe(2)
        ->and($last->created_at)->toBe(now()->format('Y-m-d H:i:s'))
        ->and($last->updated_at)->toBe(now()->format('Y-m-d H:i:s'));

});
