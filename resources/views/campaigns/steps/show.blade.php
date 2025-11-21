@extends('layouts.app')

@section('content')
<div class="container">



    {{-- Základní informace o kroku – minimalistický styl --}}
    <div class="mb-4">

        <h2 class="mb-3">{{ $step->order }}. {{ $step->name }}</h2>

        <p class="mb-2">
            <strong>Popis:</strong>
            {{ $step->description ?: '—' }}
        </p>

        <p class="mb-2">
            <strong>Koordinátor:</strong>
            @if($step->coordinator)
                {{ $step->coordinator->name }} {{ $step->coordinator->surname }}
            @else
                <span class="text-muted fst-italic">není přiřazen</span>
            @endif
        </p>

        <div class="d-flex align-items-center gap-2 mt-2">

            {{-- Tlačítko: Změnit koordinátora --}}
            <button class="btn btn-outline-primary btn-sm"
                    onclick="document.getElementById('koordinator-form').classList.toggle('d-none')">
                Změnit koordinátora
            </button>

            {{-- Tlačítko Upravit krok --}}
            <a href="{{ route('campaign.steps.edit', [
                    $campaign->id,
                    $step->id,
                    'back' => 'step'
                ]) }}"
            class="btn btn-warning">
                Upravit krok
            </a>

                <!-- zpet-->
            <a href="{{ route('topics.campaigns.show', [$campaign->topic, $campaign]) }}"
            class="btn btn-outline-secondary btn-sm mb-3">
                ← Zpět na kampaň
            </a>

        </div>

        {{-- zmena koordinatora --}}
        <div id="koordinator-form" class="d-none mt-3">
            <form method="POST"
                action="{{ route('campaigns.steps.coordinator.update', [$campaign->id, $step->id]) }}"
                class="d-flex gap-2 align-items-center">
                @csrf
                @method('PATCH')

                <select name="user_id" class="form-select w-auto">
                    <option value="">— bez koordinátora —</option>

                    @foreach($coordinators as $user)
                        <option value="{{ $user->id }}"
                            @if($step->user_id == $user->id) selected @endif>
                            {{ $user->name }} {{ $user->surname }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    Uložit
                </button>
            </form>
        </div>



   <!-- aktivity  -->

    <h3 class="mt-4">Aktivity</h3>

    <a href="{{ route('activities.create', [$campaign->id, $step->id]) }}"
       class="btn btn-success mb-3">
        + Přidat aktivitu
    </a>

    @forelse($activities as $activity)

        <div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 bg-white shadow-sm">

            {{-- LEVÁ STRANA – název + datum --}}
            <div>
                <strong>{{ $activity->name }}</strong><br>
                <small class="text-muted">
                    {{ $activity->start_date ?? '—' }}
                    –
                    {{ $activity->end_date ?? 'konec neznámý' }}
                </small>
            </div>

            {{-- PRAVÁ STRANA – tlačítka --}}
            <div class="d-flex gap-2 align-items-center">

                {{-- Upravit --}}
                <a href="{{ route('activities.edit', [$campaign->id, $step->id, $activity->id]) }}"
                class="btn btn-warning btn-sm">
                    Upravit
                </a>

                {{-- Smazat --}}
                <form method="POST"
                    action="{{ route('activities.destroy', [$campaign->id, $step->id, $activity->id]) }}"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Smazat</button>
                </form>

                {{-- Přihlášení / odhlášení --}}
                @if(auth()->check())
                    @php
                        $user = auth()->user();
                        $pivot = $activity->workers()->where('user_id', $user->id)->first()?->pivot;
                    @endphp

                    {{-- není přihlášen --}}
                    @if(!$pivot)
                        <form action="{{ route('activities.signup', $activity->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-primary btn-sm">Přihlásit se</button>
                        </form>

                    @else
                        <form action="{{ route('activities.leave', $activity->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-secondary btn-sm">Odhlásit se</button>
                        </form>

                        {{-- Stav --}}
                        @if($pivot->is_confirmed)
                            <small class="text-success fw-bold">✔ potvrzeno</small>
                        @else
                            <small class="text-warning fw-bold">⏳ čeká na potvrzení</small>
                        @endif
                    @endif
                @endif

            </div>

        </div>

    @empty
        <p class="text-muted fst-italic">Žádné aktivity zatím nejsou.</p>
    @endforelse

</div>
@endsection
