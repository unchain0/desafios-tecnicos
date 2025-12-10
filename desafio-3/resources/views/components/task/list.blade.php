@props(['tasks'])

<section class="bg-white rounded-lg shadow-md overflow-hidden">
    <header class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-700 flex items-center justify-between">
            <span>Suas Tasks</span>
            <span class="text-sm font-normal text-gray-500">
                ({{ $tasks->count() }} {{ $tasks->count() === 1 ? 'item' : 'itens' }})
            </span>
        </h2>
    </header>

    @if ($tasks->isEmpty())
        <x-empty-state
            icon="📝"
            title="Nenhuma task cadastrada."
            subtitle="Crie sua primeira task acima!"
        />
    @else
        <ul role="list" class="divide-y divide-gray-200">
            @foreach ($tasks as $task)
                <x-task.item :task="$task" />
            @endforeach
        </ul>
    @endif
</section>
