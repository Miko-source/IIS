@php
    // „Upravit“
    $sizeClass = (!empty($small) && $small) ? 'btn-sm' : '';
    $roundedClass = (!empty($small) && $small) ? 'rounded-pill' : '';
@endphp

<a href="{{ $href }}"
   class="btn btn-warning {{ $sizeClass }} {{ $roundedClass }}">
    {{ $label ?? 'Upravit' }}
</a>
