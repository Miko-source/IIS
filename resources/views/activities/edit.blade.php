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

    <form action="{{ route('activities.update', [$campaign->id, $step->id, $activity->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Název</label>
            <input type="text" name="name" class="form-control" value="{{ $activity->name }}" required>
        </div>

        <div class="mb-3">
            <label>Typ aktivity</label>
            <select name="type_id" class="form-select">
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @selected($activity->type_id == $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Náklady</label>
            <input type="number" step="0.01" name="cost" class="form-control" value="{{ $activity->cost }}" required>
        </div>

        <div class="mb-3">
            <label>Popis</label>
            <textarea name="description" class="form-control">{{ $activity->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Začátek</label>
            <input type="date" name="start_date" class="form-control" value="{{ $activity->start_date }}" required>
        </div>

        <div class="mb-3">
            <label>Konec</label>
            <input type="date" name="end_date" class="form-control" value="{{ $activity->end_date }}">
        </div>

        <button class="btn btn-primary">Uložit změny</button>
    </form>

</div>
@endsection
