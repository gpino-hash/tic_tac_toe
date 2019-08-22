<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = ['name', 'next', 'winner', 'board'];

    protected $casts = ['board' => 'array'];
}
