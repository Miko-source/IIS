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
