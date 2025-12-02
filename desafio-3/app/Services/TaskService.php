<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function getAll(): Collection
    {
        return Task::orderBy('created_at', 'desc')->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function toggle(Task $task): Task
    {
        $task->update(['done' => ! $task->done]);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
