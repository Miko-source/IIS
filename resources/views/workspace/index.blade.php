@extends('layouts.app')

@section('content')
<div class="container">
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
                            <label>Úspěšné provedení?</label>
                            <select name="success" class="form-control" required>
                                <option value="1">Ano</option>
                                <option value="0">Ne</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Zpráva / komentář</label>
                            <textarea name="content" class="form-control" rows="3" required></textarea>
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
        @foreach($closedActivities as $activity)
            <div class="card mt-3">
                <div class="card-body">

                    <h4>{{ $activity->name }}</h4>
                    <p><strong>Krok:</strong> {{ $activity->step->name }}</p>
                    <p><strong>Kampaň:</strong> {{ $activity->step->campaign->name }}</p>

                    <hr>

                    {{-- zprávy uživatele k této aktivitě --}}
                    <h5>Moje zprávy:</h5>
                    @foreach($activity->messages->where('user_id', auth()->id()) as $msg)
                        <div class="border p-2 mt-2">
                            <strong>{{ $msg->success ? 'Úspěch' : 'Neúspěch' }}</strong>
                            <p>{{ $msg->content }}</p>
                            <small class="text-muted">{{ $msg->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    @endif

</div>
@endsection
