@props(['field'])

@error($field)
    <div {{ $attributes->merge(['class' => 'mb-6 p-4 border rounded-lg bg-red-100 border-red-400 text-red-700']) }}>
        {{ $message }}
    </div>
@enderror
