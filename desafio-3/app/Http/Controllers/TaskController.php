<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task criada com sucesso!');
    }

    public function toggle(Task $task): RedirectResponse
    {
        $task->update(['done' => !$task->done]);

        $status = $task->done ? 'concluída' : 'pendente';

        return redirect()->route('tasks.index')
            ->with('success', "Task marcada como {$status}!");
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task excluída com sucesso!');
    }
}
