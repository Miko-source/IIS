@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $topic->name }}</h1>

    @if($topic->description)
        <p><strong>Popis:</strong> {{ $topic->description }}</p>
    @endif

    @if($topic->target_group)
        <p><strong>Cílová skupina:</strong> {{ $topic->target_group }}</p>
    @endif

    @if($topic->sources)
        <p><strong>Zdroje:</strong> {{ $topic->sources }}</p>
    @endif


    @if(auth()->check() && auth()->user()->isAdmin())
        <hr>
        <h4>Oprav si</h4>

        <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning">Upravit</a>

        <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger" onclick="return confirm('Opravdu smazat toto téma?')">
                Smazat
            </button>
        </form>
    @endif
    <a href="{{ route('topics.index') }}" class="btn btn-secondary">Zpět na seznam</a>

        <h3>Kampaně k tomuto tématu</h3>

    @if($campaigns->isEmpty())
        <p>Zatím nejsou žádné kampaně.</p>
    @else
        <ul class="list-group">
            @foreach($campaigns as $campaign)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $campaign->name }}</strong>
                        @if($campaign->start_date)
                            <span>(od {{ $campaign->start_date }})</span>
                        @endif
                    </div>

                    <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="btn btn-primary btn-sm">
                        Otevřít
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('topics.campaigns.create', $topic) }}" class="btn btn-success mt-2">
            + Vytvořit novou kampaň
        </a>
    @endif
</div>


@endsection
