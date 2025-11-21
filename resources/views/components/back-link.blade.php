@php
    //back-link: givi it target (oute) and label.
    $target = $target ?? ($fallback ?? url()->previous());
    $label = $label ?? '← Zpět';
@endphp

<a href="{{ $target }}" class="btn btn-outline-secondary btn-sm">
    {{ $label }}
</a>
