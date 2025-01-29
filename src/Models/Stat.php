<?php

namespace Nauticsoft\LaravelStats\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
