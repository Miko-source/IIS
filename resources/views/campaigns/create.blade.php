@extends('layouts.app')

@section('content')

    <h1>Vytvořit novou kampaň</h1>

    <p class="mb-3">
        Téma: <strong>{{ $topic->name }}</strong>
    </p>

    <form method="POST" action="{{ route('topics.campaigns.store', $topic) }}" id="campaignForm" novalidate>
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Název kampaně <span class="text-danger">*</span></label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="např. Operace dezolát"
                value="{{ old('name') }}"
                required
            >
            <div id="name_error" class="text-danger small" style="display: none;"></div>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_date_display" class="form-label">Datum začátku <span class="text-danger">*</span></label>
            <input
                type="text"
                id="start_date_display"
                class="form-control @error('start_date') is-invalid @enderror"
                placeholder="DD/MM/RRRR"
                value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('d/m/Y') : now()->format('d/m/Y') }}"
                required
            >
            <input type="hidden" id="start_date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}">
            <div class="form-text">Formát DD/MM/RRRR (den/měsíc/rok).</div>
            <div id="start_date_error" class="text-danger small" style="display: none;"></div>
            @error('start_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date_display" class="form-label">Datum konce</label>
            <input
                type="text"
                id="end_date_display"
                class="form-control @error('end_date') is-invalid @enderror"
                placeholder="DD/MM/RRRR"
                value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('d/m/Y') : '' }}"
            >
            <input type="hidden" id="end_date" name="end_date" value="{{ old('end_date') }}">
            <div id="end_date_error" class="text-danger small" style="display: none;"></div>
            @error('end_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        
        <p class="text-muted small mb-3">
            <span class="text-danger">*</span> Povinné pole
        </p>

        <button type="submit" class="btn btn-primary">Uložit kampaň</button>
        <a href="{{ route('topics.show', $topic) }}" class="btn btn-secondary">Zpět na téma</a>
    </form>

    @include('campaigns.partials.form-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initCampaignForm({
                formId: 'campaignForm',
                nameId: 'name',
                nameErrorId: 'name_error',
                startDisplayId: 'start_date_display',
                startHiddenId: 'start_date',
                startErrorId: 'start_date_error',
                endDisplayId: 'end_date_display',
                endHiddenId: 'end_date',
                endErrorId: 'end_date_error',
            });
        });
    </script>
@endsection