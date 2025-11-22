@php
    $user = auth()->user();

    // pivot může být null → ?->pivot zabrání chybě
    $pivot = $activity->workers()
        ->where('user_id', $user->id)
        ->first()
        ?->pivot;
@endphp

<!-- ahojcommit -->

{{-- === TLAČÍTKO PŘIHLÁŠENÍ / ODHÁŠENÍ === --}}
@if(!$step->is_completed)

    @if(!$pivot || $pivot->is_confirmed == 2)
        {{-- Není přihlášen nebo byl odmítnut --}}
        <form action="{{ route('activities.signup', $activity->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-primary btn-sm">Přihlásit se</button>
        </form>

    @elseif($pivot->is_confirmed == 0)
        {{-- Je přihlášen (čeká nebo schválen) --}}
        <form action="{{ route('activities.leave', $activity->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-secondary btn-sm">Odhlásit se</button>
        </form>
    @endif

@else
    {{-- Krok je dokončen --}}
    <small class="text-muted fst-italic">
        Krok je dokončen – nelze se přihlásit.
    </small>
@endif



{{-- === STAV PŘIHLÁŠKY === --}}
@if (!$pivot)
    <small class="text-muted fst-italic">Nejste přihlášen</small>

@elseif ($pivot->is_confirmed === 1)
    <small class="text-success fw-bold">✔ potvrzeno</small>

@elseif ($pivot->is_confirmed === 2)
    <small class="text-danger fw-bold">✖ odmítnuto</small>

@else
    <small class="text-warning fw-bold">⏳ čeká na potvrzení</small>
@endif

