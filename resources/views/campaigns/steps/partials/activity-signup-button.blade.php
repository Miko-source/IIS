
@php
    $user = auth()->user();
    $pivot = $activity->workers()->where('user_id', $user->id)->first()?->pivot;
@endphp

@if(!$pivot || $pivot->is_confirmed == 2)
    {{-- Není přihlášen nebo byl odmítnut --}}
    <form action="{{ route('activities.signup', $activity->id) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-primary btn-sm">Přihlásit se</button>
    </form>

@else
    {{-- Je přihlášen (čeká nebo schválen) --}}
    <form action="{{ route('activities.leave', $activity->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-secondary btn-sm">Odhlásit se</button>
    </form>
@endif


{{-- Stav potvrzení --}}
@if ($pivot->is_confirmed === 1)
    <small class="text-success fw-bold">✔ potvrzeno</small>
@elseif ($pivot->is_confirmed === 2)
    <small class="text-danger fw-bold">✖ odmítnuto</small>
@else
    <small class="text-warning fw-bold">⏳ čeká na potvrzení</small>
@endif

