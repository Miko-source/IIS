@php
    // change campaign manazer/coordinator button
    $sizeClass = (!empty($small) && $small) ? 'btn-sm' : '';
    $classes   = trim("btn btn-outline-secondary text-dark {$sizeClass}");
@endphp

@isset($href)
    <a href="{{ $href }}" class="{{ $classes }}">
        {{ $label ?? 'Akce' }}
    </a>
@else
    <button
        type="{{ $type ?? 'button' }}"
        class="{{ $classes }}"
        {!! isset($onclick) ? 'onclick="'.$onclick.'"' : '' !!}
    >
        {{ $label ?? 'Akce' }}
    </button>
@endisset
