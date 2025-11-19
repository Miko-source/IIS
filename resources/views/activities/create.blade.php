@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Přidat aktivitu ke kroku: {{ $step->name }}</h1>

    <form action="{{ route('activities.store', [$campaign->id, $step->id]) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Název</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Typ aktivity</label>
            <select name="type_id" class="form-select" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Náklady</label>
            <input type="number" step="0.01" name="cost" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Popis</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Začátek</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Konec</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <button class="btn btn-primary">Přidat aktivitu</button>
    </form>
</div>
@endsection
