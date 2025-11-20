{{-- Formulář pro úpravu kampaně (inline použitelný v show) --}}
<div class="border rounded p-3">
    <h6 class="mb-3">Upravit kampaň</h6>

    <form method="POST" action="{{ route('topics.campaigns.update', [$topic, $campaign]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Název kampaně</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $campaign->name) }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Datum začátku</label>
            <input
                type="date"
                id="start_date"
                name="start_date"
                class="form-control @error('start_date') is-invalid @enderror"
                value="{{ old('start_date', $campaign->start_date) }}"
                required
            >
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Datum konce</label>
            <input
                type="date"
                id="end_date"
                name="end_date"
                class="form-control @error('end_date') is-invalid @enderror"
                value="{{ old('end_date', $campaign->end_date) }}"
            >
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Uložit změny</button>
            <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="btn btn-secondary btn-sm">
                Zrušit
            </a>
        </div>
    </form>
</div>
