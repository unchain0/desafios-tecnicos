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

    public function markAsDone(): self
    {
        $this->done = true;

        return $this;
    }

    public function markAsPending(): self
    {
        $this->done = false;

        return $this;
    }

    public function toggle(): self
    {
        $this->done = ! $this->done;

        return $this;
    }

    public function isDone(): bool
    {
        return $this->done;
    }
}
