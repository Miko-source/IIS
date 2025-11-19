@extends('layouts.app')

@section('content')

    <h1>Vytvořit novou kampaň</h1>

    <p class="mb-3">
        Téma: <strong>{{ $topic->name }}</strong>
    </p>

    <form method="POST" action="{{ route('topics.campaigns.store', $topic) }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Název kampaně</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                required
            >
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Datum začátku</label>
            <input
                type="date"
                id="start_date"
                name="start_date"
                class="form-control"
                value="{{ old('start_date') }}"
                required
            >
            @error('start_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Datum konce</label>
            <input
                type="date"
                id="end_date"
                name="end_date"
                class="form-control"
                value="{{ old('end_date') }}"
            >
            @error('end_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Uložit kampaň</button>
        <a href="{{ route('topics.show', $topic) }}" class="btn btn-secondary">Zpět na téma</a>
    </form>
@endsection
