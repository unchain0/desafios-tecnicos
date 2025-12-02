<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'done',
    ];

    protected function casts(): array
    {
        return [
            'done' => 'boolean',
        ];
    }
}
