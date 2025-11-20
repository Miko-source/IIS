@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        @php
            $backRoute = auth()->check() && auth()->user()->isAdmin()
                ? route('admin.topics.index')
                : route('topics.index');
        @endphp
        <a href="{{ $backRoute }}" class="btn btn-outline-secondary btn-sm">
            ← Zpět na seznam
        </a>
    </div>


    <h1>{{ $topic->name }}</h1>

    @can('update', $topic)
        <div class="d-flex gap-2 my-2">
            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning btn-sm">
                Upravit téma
            </a>
            <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" onsubmit="return confirm('Opravdu chcete téma smazat?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Smazat téma</button>
            </form>
        </div>
    @endcan

    @if($topic->description)
        <p><strong>Popis:</strong> {{ $topic->description }}</p>
    @endif

    @if($topic->target_group)
        <p><strong>Cílová skupina:</strong> {{ $topic->target_group }}</p>
    @endif

    @if($topic->sources)
        <p><strong>Zdroje:</strong> {{ $topic->sources }}</p>
    @endif


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

                    <div class="d-flex gap-2">
                        <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="btn btn-primary btn-sm">
                            Otevřít
                        </a>
                        @can('update', $campaign)
                            <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_campaign=1" class="btn btn-warning btn-sm">
                                Upravit
                            </a>
                        @endcan
                        @can('delete', $campaign)
                            <form action="{{ route('topics.campaigns.destroy', [$topic, $campaign]) }}" method="POST" onsubmit="return confirm('Opravdu chcete kampaň smazat?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Smazat
                                </button>
                            </form>
                        @endcan
                    </div>
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
