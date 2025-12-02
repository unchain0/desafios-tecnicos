<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(StoreTaskRequest $request): JsonResponse|RedirectResponse
    {
        $task = Task::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json($task, 201);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task criada com sucesso!');
    }

    public function toggle(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $task->update(['done' => !$task->done]);

        if ($request->wantsJson()) {
            return response()->json($task);
        }

        $status = $task->done ? 'concluída' : 'pendente';

        return redirect()->route('tasks.index')
            ->with('success', "Task marcada como {$status}!");
    }

    public function destroy(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $task->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task excluída com sucesso!');
    }
}
