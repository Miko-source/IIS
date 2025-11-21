@php
    //back-link: giv it target (route) and label.
    $target = $target ?? ($fallback ?? url()->previous());
    $label = $label ?? '← Zpět';
@endphp

<a href="{{ $target }}" class="btn btn-outline-secondary btn-sm">
    {{ $label }}
</a>
