<?php

namespace App\Livewire;

use App\Models\Task;
use App\Services\TaskService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TaskManager extends Component
{
    public const MAX_TITLE_LENGTH = 255;

    #[Validate('required|string|max:'.self::MAX_TITLE_LENGTH, message: [
        'required' => 'O título da task é obrigatório.',
        'max' => 'O título deve ter no máximo '.self::MAX_TITLE_LENGTH.' caracteres.',
    ])]
    public string $title = '';

    public string $message = '';

    public function addTask(TaskService $taskService): void
    {
        $this->validate();

        $taskService->create($this->getTaskData());

        $this->resetForm();
        $this->flash('Task criada com sucesso!');
    }

    private function getTaskData(): array
    {
        return ['title' => $this->title];
    }

    private function resetForm(): void
    {
        $this->title = '';
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
