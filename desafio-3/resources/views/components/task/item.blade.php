@props(['task'])

<li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors" wire:key="{{ $task->id }}">
    <div class="flex items-center gap-3 flex-1 min-w-0">
        <span class="text-xl">{{ $task->done ? '✅' : '⬜' }}</span>
        <span class="{{ $task->done ? 'line-through text-gray-400' : 'text-gray-700' }} truncate">
            {{ $task->title }}
        </span>
    </div>

    <div class="ml-4 shrink-0 flex gap-2">
        <button wire:click="toggleTask({{ $task->id }})" wire:loading.attr="disabled"
            wire:target="toggleTask({{ $task->id }})"
            class="{{ $task->done
                ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                : 'bg-green-100 text-green-700 hover:bg-green-200' }}
                px-4 py-2 text-sm font-medium rounded-lg transition-colors disabled:opacity-50">
            {{ $task->done ? 'Reabrir' : 'Concluir' }}
        </button>

        <button @click="$dispatch('confirm-delete', { action: { method: 'deleteTask', params: {{ $task->id }} } })"
            wire:loading.attr="disabled" wire:target="deleteTask({{ $task->id }})"
            class="bg-red-100 text-red-700 hover:bg-red-200 px-4 py-2 text-sm font-medium rounded-lg transition-colors disabled:opacity-50">
            Excluir
        </button>
    </div>
</li>
