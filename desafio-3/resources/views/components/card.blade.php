@props(['class' => ''])

<div {{ $attributes->merge(['class' => "bg-white rounded-lg shadow-md {$class}"]) }}>
    {{ $slot }}
</div>
