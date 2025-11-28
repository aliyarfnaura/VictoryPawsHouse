@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            {{-- Gunakan {!! !!} agar tag <strong> terbaca sebagai HTML --}}
            <li class="font-bold">{!! $message !!}</li> 
        @endforeach
    </ul>
@endif