<?php

namespace App\Livewire;

use App\Models\Task;
use App\Services\TaskService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TaskManager extends Component
{
    #[Validate('required|string|max:255', message: [
        'required' => 'O título da task é obrigatório.',
        'max' => 'O título deve ter no máximo 255 caracteres.',
    ])]
    public string $title = '';

    public string $message = '';

    public function addTask(TaskService $taskService): void
    {
        $this->validate();

        $taskService->create(['title' => $this->title]);

        $this->title = '';
        $this->flash('Task criada com sucesso!');
    }

    public function toggleTask(Task $task, TaskService $taskService): void
    {
        $taskService->toggle($task);
    }

    public function deleteTask(Task $task, TaskService $taskService): void
    {
        $taskService->delete($task);
        $this->flash('Task excluída com sucesso!');
    }

    public function flash(string $message): void
    {
        $this->message = $message;
        $this->dispatch('clear-message');
    }

    public function clearMessage(): void
    {
        $this->message = '';
    }

    public function render(TaskService $taskService)
    {
        return view('livewire.task-manager', [
            'tasks' => $taskService->getAll(),
        ]);
    }
}
