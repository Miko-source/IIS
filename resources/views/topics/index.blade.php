@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dostupná témata</h1>

    @if($topics->isEmpty())
        <p>Žádná témata zatím nebyla vytvořena.</p>
    @else
        <div class="d-grid gap-2">
        @foreach($topics as $topic)
            <a href="{{ route('topics.show', $topic) }}" class="btn-topic">
                {{ $topic->name }}
            </a>
        @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $topics->links() }}
        </div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
    ← Zpět na dashboard
    </a>

</div>
@endsection
