@extends('layouts.app')

@section('content')
<h1>Témata</h1>
<a href="{{ route('admin.topics.create') }}" class="btn btn-primary">Přidat téma</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>Název</th>
            <th>Cílová skupina</th>
            <th>Akce</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topics as $topic)
            <tr>
                <td>{{ $topic->name }}</td>
                <td>{{ $topic->target_group }}</td>
                <td>
                    <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning btn-sm">Upravit</a>
                    <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Smazat</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
