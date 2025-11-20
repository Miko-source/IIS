@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dostupná témata</h1>

    @if($topics->isEmpty())
        <p>Žádná témata zatím nebyla vytvořena.</p>
    @else
        <div class="d-grid gap-2">
        @foreach($topics as $topic)
            <a href="{{ route('topics.show', $topic) }}"
                class="btn btn-outline-primary text-start fw-semibold"
                style="padding: 12px 18px; font-size: 16px;">
                {{ $topic->name }}
            </a>
        @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $topics->links() }}
        </div>
    @endif
</div>
@endsection
