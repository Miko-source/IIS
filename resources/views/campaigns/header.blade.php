{{-- Hlavička kampaně --}}
<h1>Kampaň: {{ $campaign->name }}</h1>

<p><strong>Téma:</strong> {{ $topic->name }}</p>

@if($campaign->start_date)
    <p><strong>Začátek:</strong> {{ $campaign->start_date }}</p>
@endif

@if($campaign->end_date)
    <p><strong>Konec:</strong> {{ $campaign->end_date }}</p>
@endif