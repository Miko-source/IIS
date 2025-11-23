@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Zpět na krok --}}
    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
       class="btn btn-outline-secondary btn-sm mb-3">
        ← Zpět na krok
    </a>

    <h1>Detail aktivity</h1>

    <div class="card mt-3">
        <div class="card-header">
            <strong>{{ $activity->name }}</strong>
        </div>

        <div class="card-body">
                    <p>
            <strong>Stav:</strong>
            @if($activity->completed)
                <span class="text-success">Splněno</span>
            @else
                <span class="text-warning">Nesplněno</span>
            @endif
        </p>


            <p>
                <strong>Popis:</strong><br>
                {{ $activity->description ?: '—' }}
            </p>

            <p>
                <strong>Datum začátku:</strong>
                {{ $activity->start_date ? $activity->start_date->format('d.m.Y') : '—' }}
            </p>

            <p>
                <strong>Datum konce:</strong>
                {{ $activity->end_date ? $activity->end_date->format('d.m.Y') : '—' }}
            </p>

            <p>
                <strong>Náklady:</strong>
                {{ $activity->cost ? $activity->cost . ' Kč' : '—' }}
            </p>

            <p>
                <strong>Realizátoři:</strong><br>
                @if($activity->workers->count())
                    @foreach ($activity->approvedUsers as $worker)

                        • {{ $worker->name }} {{ $worker->surname }}<br>
                    @endforeach
                @else
                    <em>Žádní přihlášení realizátoři</em>
                @endif
            </p>

        </div>
    </div>

    {{-- Tlačítka dole --}}
    <div class="mt-4 d-flex gap-2">

        {{-- Upravit aktivitu --}}
        <a href="{{ route('activities.edit', [$campaign->id, $step->id, $activity->id]) }}"
           class="btn btn-warning">
            Upravit aktivitu
        </a>

        {{-- Přihlásit se / Odhlásit se jako pracovník – jen pokud existuje logika --}}
        @if(auth()->user()->can('registerOnActivity', $activity))
            <a href="{{ route('activities.register', [$campaign->id, $step->id, $activity->id]) }}"
               class="btn btn-primary">
                Přihlásit se k aktivitě
            </a>
        @endif

    </div>

</div>
@endsection
