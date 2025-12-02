@extends('layouts.app')

@section('content')
    <div x-data="taskManager()" x-cloak>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📋 Task Manager</h1>
            <p class="text-gray-600">Gerencie suas tarefas de forma simples</p>
        </div>

        <template x-if="message">
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg"
                 x-text="message"
                 x-transition
                 x-init="setTimeout(() => message = '', 3000)">
            </div>
        </template>

        <template x-if="error">
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg"
                 x-text="error"
                 x-transition>
            </div>
        </template>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Nova Task</h2>
            <form @submit.prevent="addTask" class="flex gap-3">
                <input
                    type="text"
                    x-model="newTitle"
                    placeholder="Digite o título da task..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                    maxlength="255"
                    :disabled="loading"
                >
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50"
                    :disabled="loading || !newTitle.trim()"
                >
                    <span x-show="!loading">Adicionar</span>
                    <span x-show="loading">...</span>
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">
                    Suas Tasks
                    <span class="text-sm font-normal text-gray-500">
                        (<span x-text="tasks.length"></span> <span x-text="tasks.length === 1 ? 'item' : 'itens'"></span>)
                    </span>
                </h2>
            </div>

            <template x-if="tasks.length === 0">
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-400 text-5xl mb-4">📝</div>
                    <p class="text-gray-500">Nenhuma task cadastrada.</p>
                    <p class="text-gray-400 text-sm mt-1">Crie sua primeira task acima!</p>
                </div>
            </template>

            <ul class="divide-y divide-gray-200">
                <template x-for="task in tasks" :key="task.id">
                    <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <span class="text-xl" x-text="task.done ? '✅' : '⬜'"></span>
                            <span :class="task.done ? 'line-through text-gray-400' : 'text-gray-700'"
                                  class="truncate"
                                  x-text="task.title">
                            </span>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex gap-2">
                            <button
                                @click="toggleTask(task)"
                                :class="task.done
                                    ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                                    : 'bg-green-100 text-green-700 hover:bg-green-200'"
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                                x-text="task.done ? 'Reabrir' : 'Concluir'">
                            </button>
                            <button
                                @click="deleteTask(task)"
                                class="bg-red-100 text-red-700 hover:bg-red-200 px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                                Excluir
                            </button>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

        <div class="mt-8 text-center text-gray-400 text-sm">
            Laravel Task Manager — Desafio Técnico
        </div>
    </div>

    <script>
        function taskManager() {
            return {
                tasks: @json($tasks),
                newTitle: '',
                loading: false,
                message: '',
                error: '',
                csrfToken: document.querySelector('meta[name="csrf-token"]').content,

                async addTask() {
                    if (!this.newTitle.trim()) return;

                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await fetch('/tasks', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({ title: this.newTitle })
                        });

                        if (!response.ok) throw new Error('Erro ao criar task');

                        const task = await response.json();
                        this.tasks.unshift(task);
                        this.newTitle = '';
                        this.message = 'Task criada com sucesso!';
                        setTimeout(() => this.message = '', 3000);
                    } catch (e) {
                        this.error = 'Erro ao criar task. Tente novamente.';
                    } finally {
                        this.loading = false;
                    }
                },

                async toggleTask(task) {
                    try {
                        const response = await fetch(`/tasks/${task.id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });

                        if (!response.ok) throw new Error('Erro ao atualizar task');

                        const updated = await response.json();
                        task.done = updated.done;
                    } catch (e) {
                        this.error = 'Erro ao atualizar task.';
                    }
                },

                async deleteTask(task) {
                    try {
                        const response = await fetch(`/tasks/${task.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });

                        if (!response.ok) throw new Error('Erro ao excluir task');

                        this.tasks = this.tasks.filter(t => t.id !== task.id);
                        this.message = 'Task excluída com sucesso!';
                        setTimeout(() => this.message = '', 3000);
                    } catch (e) {
                        this.error = 'Erro ao excluir task.';
                    }
                }
            }
        }
    </script>
@endsection
