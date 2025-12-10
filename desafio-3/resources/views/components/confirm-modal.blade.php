@props([
    'title' => 'Confirmar ação',
    'message' => 'Tem certeza que deseja continuar?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
])

<div
    x-data="{
        open: false,
        action: null,
        handleConfirm() {
            if (this.action) {
                $wire.call(this.action.method, this.action.params);
            }
            this.open = false;
        }
    }"
    x-on:confirm-delete.window="open = true; action = $event.detail.action"
    x-on:keydown.escape.window="open = false"
    x-cloak
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-40"
        @click="open = false"
    ></div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.stop>
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <span class="text-2xl">⚠️</span>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                {{ $title }}
            </h3>

            <p class="text-gray-600 text-center mb-6">
                {{ $message }}
            </p>

            <div class="flex gap-3 justify-center">
                <button
                    @click="open = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors"
                >
                    {{ $cancelText }}
                </button>
                <button
                    @click="handleConfirm"
                    class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors"
                >
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>
