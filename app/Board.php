<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    const X = 1;

    const O = 2;

    protected $fillable = ['name', 'next', 'winner', 'board'];

    protected $casts = ['board' => 'array'];
}
