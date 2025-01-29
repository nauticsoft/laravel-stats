<?php

namespace Nauticsoft\LaravelStats\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read Carbon $timestamp
 * @property-read string $type
 * @property-read string $key
 * @property-read int $value
 */
class Stat extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function casts(): array
    {
        return [
            'timestamp' => 'immutable_datetime',
            'value' => 'integer',
        ];
    }
}
