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
        $task->toggle();
        $task->save();

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function markAsDone(Task $task): Task
    {
        $task->markAsDone();
        $task->save();

        return $task;
    }

    public function markAsPending(Task $task): Task
    {
        $task->markAsPending();
        $task->save();

        return $task;
    }

    public function isTaskDone(Task $task): bool
    {
        return $task->isDone();
    }
}
