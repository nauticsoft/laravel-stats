<?php

use Nauticsoft\LaravelStats\Facades\Stats;

it('gets stats by type', function () {
    Stats::type('requests')
        ->save(now(), 'count', 5)
        ->save(now()->subDay(), 'count', 3);
    Stats::type('commits')
        ->save(now(), 'count', 5);

    $rows = Stats::type('requests')->get();

    expect($rows->toArray())->toBe([
        ['date' => now()->format('Y-m-d'), 'key' => 'count', 'value' => 5],
        ['date' => now()->subDay()->format('Y-m-d'), 'key' => 'count', 'value' => 3],
    ]);
});

it('cannot get stats without type', function () {
    Stats::get();
})->throws(UnexpectedValueException::class);

it('filters stats by start date', function () {
    Stats::type('requests')
        ->save(now(), 'count', 5)
        ->save(now()->subDay(), 'count', 3);

    $rows = Stats::type('requests')
        ->from(now())
        ->get();

    expect($rows->toArray())->toBe([
        ['date' => now()->format('Y-m-d'), 'key' => 'count', 'value' => 5],
    ]);
});

it('filters stats by end date', function () {
    Stats::type('requests')
        ->save(now(), 'count', 5)
        ->save(now()->subDay(), 'count', 3);

    $rows = Stats::type('requests')
        ->until(now()->subDay())
        ->get();

    expect($rows->toArray())->toBe([
        ['date' => now()->subDay()->format('Y-m-d'), 'key' => 'count', 'value' => 3],
    ]);
});

it('orders stats by date desc by default', function () {
    Stats::type('requests')
        ->save(now()->subDay(), 'count', 3)
        ->save(now(), 'count', 5);

    $rows = Stats::type('requests')->get();

    expect($rows->toArray())->toBe([
        ['date' => now()->format('Y-m-d'), 'key' => 'count', 'value' => 5],
        ['date' => now()->subDay()->format('Y-m-d'), 'key' => 'count', 'value' => 3],
    ]);
});

it('can change the date format', function () {
    Stats::type('requests')
        ->dateFormat('Y')
        ->save(now(), 'count', 5);

    $rows = Stats::type('requests')->get();

    expect($rows[0]['date'])->toBe(date('Y'));
});
