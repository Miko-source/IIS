@php
    $sizeClass = (!empty($small) && $small) ? 'btn-sm rounded-pill' : '';
    $label = $label ?? 'Smazat';
    $confirm = $confirm ?? 'Opravdu chcete smazat?';
@endphp

<form action="{{ $action }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('{{ $confirm }}');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger {{ $sizeClass }}">
        {{ $label }}
    </button>
</form>
