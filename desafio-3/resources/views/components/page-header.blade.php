@props([
    'icon' => '',
    'title' => '',
    'subtitle' => '',
])

<header class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-2">
        @if ($icon)
            <span>{{ $icon }}</span>
        @endif
        <span>{{ $title }}</span>
    </h1>

    @if ($subtitle)
        <p class="text-gray-600 mt-1">{{ $subtitle }}</p>
    @endif
</header>
