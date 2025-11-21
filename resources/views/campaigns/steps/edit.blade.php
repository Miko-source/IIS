@extends('layouts.app')

@section('content')
<div class="container">

    <div>
        @include('components.back-link', [
            'target' => route('topics.campaigns.show', [$campaign->topic, $campaign]),
            'label' => '← Zpět na kampaň'
        ])
    </div>

    <h1 class="mb-3">Upravit krok</h1>

    <form method="POST" action="{{ route('campaign.steps.update', [$campaign->id, $step->id]) }}">
        @csrf
        @method('PUT')

        <div>
            <label class="form-label">Název kroku</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $step->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pořadí</label>
            <input type="number" name="order" class="form-control"
                   value="{{ old('order', $step->order) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Koordinátor</label>
            <select name="user_id" class="form-select" required>
                @foreach($coordinators as $coord)
                    <option value="{{ $coord->id }}"
                        @selected($coord->id == $step->user_id)>
                        {{ $coord->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Popis</label>
            <textarea name="description" class="form-control">{{ old('description', $step->description) }}</textarea>
        </div>
        <button class="btn btn-primary">Uložit změny</button>
    </form>
</div>
@endsection
