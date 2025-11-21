
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
    {{-- je přihlášen --}}
    <form action="{{ route('activities.leave', $activity->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-secondary btn-sm">Odhlásit se</button>
    </form>

    {{-- Stav potvrzení --}}
    @if($pivot->is_confirmed)
        <small class="text-success fw-bold">✔ potvrzeno</small>
    @else
        <small class="text-warning fw-bold">⏳ čeká na potvrzení</small>
    @endif
@endif