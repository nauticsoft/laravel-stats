<?php

use Carbon\CarbonImmutable;
use Nauticsoft\LaravelStats\Models\Stat;

test('to array', function () {
    $stat = Stat::create([
        'timestamp' => now(),
        'type' => 'test-type',
        'key' => 'test-key',
        'value' => 1000,
    ])->fresh();

    expect(array_keys($stat->toArray()))->toBe([
        'id',
        'timestamp',
        'type',
        'key',
        'value',
    ]);
});

test('casts', function () {
    $stat = Stat::create([
        'timestamp' => now(),
        'type' => 'test-type',
        'key' => 'test-key',
        'value' => 1000,
    ])->fresh();

    expect($stat->timestamp)->toBeInstanceof(CarbonImmutable::class);
    expect($stat->value)->toBeInt();
});
