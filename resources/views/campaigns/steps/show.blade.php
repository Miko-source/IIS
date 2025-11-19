@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Detail kroku</h1>

    <h3>{{ $step->order }}. {{ $step->name }}</h3>

    <p><strong>Popis:</strong> {{ $step->description ?? '—' }}</p>

    <p><strong>Koordinátor:</strong>
        {{ $step->coordinator->name ?? '—' }}
    </p>

    <hr>
        <h3>Aktivity</h3>

    <a href="{{ route('activities.create', [$campaign->id, $step->id]) }}" class="btn btn-primary mb-3">
        Přidat aktivitu
    </a>

    @forelse($activities as $activity)
       
    
        <div class="card mb-2">
            <div class="card-body">
                <h5>{{ $activity->name }}</h5>
                <p>{{ $activity->description }}</p>
                <p><strong>Typ:</strong> {{ $activity->type->name }}</p>
                <p><strong>Náklady:</strong> {{ $activity->cost }} Kč</p>

                <a href="{{ route('activities.edit', [$campaign->id, $step->id, $activity->id]) }}"
                class="btn btn-warning btn-sm">Upravit</a>

                <form method="POST" action="{{ route('activities.destroy', [$campaign->id, $step->id, $activity->id]) }}"
                    style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Smazat</button>
                    
                </form>
<!-- prihlasovani aktivity -->
             @if(auth()->check())
            @php
            $user = auth()->user();
            @endphp
              @php
                // Má uživatel záznam v activity_user?
                $pivot = $activity->workers()->where('user_id', $user->id)->first()?->pivot;
            @endphp

            {{-- Uživatel ještě není přihlášen --}}
            @if(!$pivot)
                <form action="{{ route('activities.signup', $activity->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">Přihlásit se</button>
                </form>

            {{-- Uživatel JE přihlášen --}}
            @else
                <div class="d-flex align-items-center gap-3">

                    {{-- Tlačítko odhlásit (detach) --}}
                    <form action="{{ route('activities.leave', $activity->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Odhlásit se</button>
                    </form>

                    {{-- Text o stavu přihlášení --}}
                    @if($pivot->is_confirmed)
                        <span class="text-success fw-bold"> Potvrzeno koordinátorem</span>
                    @else
                        <span class="text-warning fw-bold"> Čeká se na potvrzení</span>
                    @endif

                </div>
            @endif

        @endif
        <!-- konec prihlasovani -->


            </div>
        </div>
    @empty
        <p>Žádné aktivity zatím nejsou.</p>
    @endforelse


    <a href="{{ route('campaign.steps.edit', [$campaign->id, $step->id]) }}"
       class="btn btn-warning">
        Upravit krok
    </a>

    <a href="{{ route('campaign.steps.index', $campaign->id) }}"
       class="btn btn-secondary">
        Zpět na seznam kroků
    </a>

</div>
@endsection
