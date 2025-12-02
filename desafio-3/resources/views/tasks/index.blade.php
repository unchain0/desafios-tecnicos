@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📋 Task Manager</h1>
        <p class="text-gray-600">Gerencie suas tarefas de forma simples</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Nova Task</h2>
        <form action="{{ route('tasks.store') }}" method="POST" class="flex gap-3">
            @csrf
            <input
                type="text"
                name="title"
                placeholder="Digite o título da task..."
                value="{{ old('title') }}"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
                maxlength="255"
            >
            <button
                type="submit"
                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                Adicionar
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700">
                Suas Tasks
                <span class="text-sm font-normal text-gray-500">({{ $tasks->count() }} {{ $tasks->count() === 1 ? 'item' : 'itens' }})</span>
            </h2>
        </div>

        @if ($tasks->isEmpty())
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 text-5xl mb-4">📝</div>
                <p class="text-gray-500">Nenhuma task cadastrada.</p>
                <p class="text-gray-400 text-sm mt-1">Crie sua primeira task acima!</p>
            </div>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($tasks as $task)
                    <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <span class="text-xl">
                                @if ($task->done)
                                    ✅
                                @else
                                    ⬜
                                @endif
                            </span>
                            <span class="{{ $task->done ? 'line-through text-gray-400' : 'text-gray-700' }} truncate">
                                {{ $task->title }}
                            </span>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex gap-2">
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="{{ $task->done
                                        ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                                        : 'bg-green-100 text-green-700 hover:bg-green-200'
                                    }} px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                                >
                                    {{ $task->done ? 'Reabrir' : 'Concluir' }}
                                </button>
                            </form>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-100 text-red-700 hover:bg-red-200 px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                                >
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-8 text-center text-gray-400 text-sm">
        Laravel Task Manager — Desafio Técnico
    </div>
@endsection
