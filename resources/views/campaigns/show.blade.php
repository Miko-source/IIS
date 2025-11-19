@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $campaign->name }}</h1>

    <p><strong>Téma:</strong> {{ $topic->name }}</p>

    @if($campaign->start_date)
        <p><strong>Začátek:</strong> {{ $campaign->start_date }}</p>
    @endif

    @if($campaign->end_date)
        <p><strong>Konec:</strong> {{ $campaign->end_date }}</p>
    @endif

    {{-- SEKCE PRO SPRÁVU SPRÁVCE --}}
    @can('manageManager', App\Models\Campaign::class)
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title">Správce kampaně</h5>
                
                @if($campaign->user_id && !request()->has('edit_manager'))
                    <p class="mb-2">
                        <strong>{{ $campaign->manager->name }}</strong> 
                        <span class="text-muted">({{ $campaign->manager->email }})</span>
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_manager=1" class="btn btn-warning btn-sm">
                            Změnit správce
                        </a>
                        <form method="POST" action="{{ route('topics.campaigns.manager.destroy', [$topic, $campaign]) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Opravdu odebrat správce?')">
                                Odebrat správce
                            </button>
                        </form>
                    </div>
                @elseif(!$campaign->user_id && !request()->has('edit_manager'))
                    <p class="text-muted mb-2">Žádný správce přiřazen</p>
                    <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_manager=1" class="btn btn-success btn-sm">
                        Přiřadit správce
                    </a>
                @endif

                {{-- FORMULÁŘ PRO ZMĚNU/PŘIŘAZENÍ SPRÁVCE --}}
                @if(request()->has('edit_manager'))
                    <div class="border-top pt-3 mt-3">
                        @if($campaign->user_id)
                            <h6 class="mb-3">Aktuální správce: <strong>{{ $campaign->manager->name }}</strong></h6>
                            <h6 class="mb-3">Změnit správce</h6>
                        @else
                            <h6 class="mb-3">Přiřadit správce</h6>
                        @endif

                        <form method="POST" action="{{ route('topics.campaigns.manager.update', [$topic, $campaign]) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="user_id" class="form-label">
                                    {{ $campaign->user_id ? 'Nový správce' : 'Správce kampaně' }}
                                </label>
                                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">-- Vyberte uživatele --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ $campaign->user_id ? 'Změnit správce' : 'Přiřadit správce' }}
                                </button>
                                <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="btn btn-secondary">
                                    Zrušit
                                </a>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endcan

    <a href="{{ route('campaign.steps.index', $campaign->id) }}" class="btn btn-outline-primary">
        Spravovat kroky kampaně
    </a>

    @can('update', $campaign)
        <div class="d-flex gap-2 my-3">
            <a href="{{ route('topics.campaigns.edit', [$topic, $campaign]) }}" class="btn btn-primary">
                Upravit kampaň
            </a>
            <form action="{{ route('topics.campaigns.destroy', [$topic, $campaign]) }}" method="POST" onsubmit="return confirm('Opravdu chcete kampaň smazat?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Smazat kampaň</button>
            </form>
        </div>
    @endcan

    <a href="{{ route('topics.show', $topic) }}" class="btn btn-secondary mt-3">
        ← Zpět na téma
    </a>

</div>
@endsection