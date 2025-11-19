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

    <a href="{{ route('topics.show', $topic) }}" class="btn btn-secondary mt-3">
        ← Zpět na téma
    </a>

</div>
@endsection
