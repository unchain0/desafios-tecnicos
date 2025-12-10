<section class="bg-white rounded-lg shadow-md p-6 mb-6" wire:ignore.self>
    <header class="mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Nova Task</h2>
    </header>

    <form wire:submit="addTask" class="flex flex-col sm:flex-row gap-3">
        <input
            type="text"
            wire:model="title"
            placeholder="Digite o título da task..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            maxlength="255"
            aria-label="Título da task"
        >
        <button
            type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 whitespace-nowrap"
            wire:loading.attr="disabled"
            wire:target="addTask"
        >
            <span wire:loading.remove wire:target="addTask">Adicionar</span>
            <span wire:loading wire:target="addTask">Adicionando...</span>
        </button>
    </form>
</section>
