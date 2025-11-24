@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-3">
        @include('components.back-link', [
            'target' => route('campaign.steps.show', [$campaign->id, $step->id]),
            'label' => '← Zpět na krok'
        ])
    </div>

    <h1>Upravit aktivitu: {{ $activity->name }}</h1>

    @php
        $startValue = old('start_date', $activity->start_date?->format('Y-m-d'));
        $endValue = old('end_date', $activity->end_date?->format('Y-m-d'));
    @endphp

    <form action="{{ route('activities.update', [$campaign->id, $step->id, $activity->id]) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Název *</label>
            <input 
                type="text" 
                placeholder="mytí auta, úklid parku, ... "
                name="name" 
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $activity->name) }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="invalid-feedback">Prosím vyplňte toto pole.</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Typ aktivity *</label>
            <select 
                name="type_id" 
                class="form-select @error('type_id') is-invalid @enderror"
                required
            >
                <option value="">— Vyberte typ —</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @selected(old('type_id', $activity->type_id) == $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            @error('type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="invalid-feedback">Prosím vyberte typ.</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Náklady (Kč)</label>
            <input 
                type="number" 
                placeholder="např. 1500.50"
                step="0.01" 
                min="0"
                name="cost" 
                class="form-control @error('cost') is-invalid @enderror"
                value="{{ old('cost', $activity->cost) }}"
            >
            @error('cost')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Popis</label>
            <textarea 
                name="description" 
                placeholder="Popis aktivity"
                class="form-control @error('description') is-invalid @enderror"
                rows="4"
            >{{ old('description', $activity->description) }}</textarea>

            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Začátek *</label>
            <input 
                type="date" 
                name="start_date" 
                class="form-control @error('start_date') is-invalid @enderror"
                value="{{ $startValue }}"
                id="start_date"
                required
            >
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="invalid-feedback">Prosím zadejte datum začátku.</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Konec</label>
            <input 
                type="date" 
                name="end_date" 
                class="form-control @error('end_date') is-invalid @enderror"
                value="{{ $endValue }}" 
                id="end_date"
                @if($startValue) min="{{ $startValue }}" @endif
            >
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Datum ukončení musí být stejné nebo pozdější než datum začátku. Pokud nechcete nastavit konec, ponechte prázdné.</div>
        </div>

        <button class="btn btn-primary">Uložit změny</button>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        if (!startInput || !endInput) {
            return;
        }

        function syncEndDate() {
            if (!startInput.value) {
                endInput.min = '';
                return;
            }

            endInput.min = startInput.value;

            if (endInput.value && endInput.value < startInput.value) {
                endInput.value = startInput.value;
            }
        }

        startInput.addEventListener('input', syncEndDate);
        startInput.addEventListener('change', syncEndDate);

        endInput.addEventListener('input', syncEndDate);
        endInput.addEventListener('change', syncEndDate);
        endInput.addEventListener('blur', syncEndDate);

        syncEndDate();
    });
</script>


@endsection
