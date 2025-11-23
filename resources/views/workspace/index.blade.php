@extends('layouts.app')

@section('content')
<div class="container">

    <div>
        @include('components.back-link', [
            'target' => route('dashboard'),
            'label' => '← Zpět na Dashboard'
        ])
    </div>
    <h1>Moje aktivity</h1>

    {{-- ===================  AKTIVNÍ AKTIVITY  =================== --}}
    <h3>Aktivní aktivity</h3>

    @if($assigned->isEmpty())
        <p class="text-muted">Nemáte žádné aktivní aktivity.</p>
    @else
        @foreach($assigned as $item)
            <div class="card mt-3">
                <div class="card-body">
                    <h4>{{ $item->activity->name }}</h4>
                    <p><strong>Krok:</strong> {{ $item->activity->step->name }}</p>
                    <p><strong>Kampaň:</strong> {{ $item->activity->step->campaign->name }}</p>

                    <hr>

                    {{-- formulář --}}
                    <form method="post" action="{{ route('workspace.report', $item->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Stav dokončení aktivity:</label>
                            <select name="success" class="form-control @error('success') is-invalid @enderror" required>
                                <option value="">-- Vyberte stav --</option>
                                <option value="1">✓ Úspěšně dokončeno</option>
                                <option value="0">✗ Neúspěšné / selhalo</option>
                            </select>
                            @error('success')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Zpráva / komentář</label>
                            <textarea 
                                name="content" 
                                class="form-control @error('content') is-invalid @enderror" 
                                rows="4" 
                                required
                                placeholder="Popište průběh aktivity..."></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-success">Odeslat zprávu</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif


    {{-- ===================  UZAVŘENÉ AKTIVITY  =================== --}}
    <h3 class="mt-5">Uzavřené aktivity</h3>

    @if($closedActivities->isEmpty())
        <p class="text-muted">Nemáte žádné uzavřené aktivity.</p>
    @else
        @foreach($closedActivities->filter(function($a) {
    return $a->workers()
             ->where('user_id', auth()->id())
             ->where('is_completed', 1)
             ->exists();  }) as $activity)
            <div class="card mt-3">
                <div class="card-body">

                    <h4>{{ $activity->name }}</h4>
                    <p><strong>Krok:</strong> {{ $activity->step->name }}</p>
                    <p><strong>Kampaň:</strong> {{ $activity->step->campaign->name }}</p>

                    <hr>

                    {{-- zprávy uživatele k této aktivitě --}}
                    <h5>Moje zprávy:</h5>
                    @foreach($activity->messages->where('user_id', auth()->id()) as $msg)
                        <div class="border-start border-3 ps-3 mb-2 p-2
                            @if($msg->success === 1) border-success
                            @elseif($msg->success === 0) border-danger
                            
                            @endif">
                            <strong>
                                @if($msg->success === 1)
                                    <span class="text-success">✓ Úspěch</span>
                                @elseif($msg->success === 0)
                                    <span class="text-danger">✗ Neúspěch</span>
                            
                                @endif
                            </strong>
                            <p class="mb-1">{{ $msg->content }}</p>
                            <small class="text-muted">{{ $msg->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    @endif

</div>
@endsection
