@php
    $baseClasses = "inline-flex items-center justify-center rounded-md px-3 py-2 text-xs font-medium text-white transition {$buttonClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $buttonType }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </button>
@endif
