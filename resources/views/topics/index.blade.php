@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dostupná témata</h1>

    @if($topics->isEmpty())
        <p>Žádná témata zatím nebyla vytvořena.</p>
    @else
        <ul class="list-group">
            @foreach($topics as $topic)
                <li class="list-group-item">
                    <a href="{{ route('topics.show', $topic) }}">
                        {{ $topic->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
<div>
@if(auth()->check() && auth()->user()->isAdmin())


    

    <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
        Spravovat temata
    </a>
@endif
</div>
@endsection
