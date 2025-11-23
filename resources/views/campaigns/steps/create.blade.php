@extends('layouts.app')

@section('content')
<div class="container">
         <div class="mb-3">
        @include('components.back-link', [
    'target' => auth()->check() && auth()->user()->isAdmin()
        ? route('admin.topics.index')
        :route('campaign.steps.index', $campaign->id),
    'label' => '← Zpět na správu kroků'
])

    </div>
    <h1>Přidat krok ke kampani: {{ $campaign->name }}</h1>

    <form action="{{ route('campaign.steps.store', $campaign->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Název kroku</label>
            <input type="text" placeholder="Příprava materiálů" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pořadí kroku</label>
            <input type="number" placeholder="např. 1" name="order" class="form-control" value="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Koordinátor</label>
            <select name="user_id" class="form-select" required>
                @foreach($coordinators as $coord)
                    <option value="{{ $coord->id }}">{{ $coord->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Popis</label>
            <textarea name="description" placeholder="Popis kroku..." class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Vytvořit krok</button>
    </form>
</div>
@endsection
