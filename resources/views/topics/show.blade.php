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

    <a href="{{ route('topics.index') }}" class="btn btn-secondary">Zpět na seznam</a>




</div>

<div>
    @if(auth()->check() && auth()->user()->isAdmin())
    <hr>
    <h4>Oprav si </h4>

    <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning">Upravit</a>

    <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger"
                onclick="return confirm('Opravdu smazat toto téma?')">
            Smazat
        </button>

@endif
</div>


@endsection
