@extends('layouts.app')

@section('content')
<div>
    @include('components.back-link', [
        'target' => route('dashboard'),
        'label' => '← Zpět na dashboard'
    ])
</div>

<h1>Témata</h1>
<a href="{{ route('admin.topics.create') }}" class="btn-app btn-app-primary">
    Přidat téma
</a>

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
                <td>
                    <a href="{{ route('topics.show', $topic) }}"
                       class="btn btn-outline-primary text-start fw-semibold w-100"
                       style="padding: 12px 18px; font-size: 16px;">
                        {{ $topic->name }}
                    </a>
                </td>
                <td>{{ $topic->target_group }}</td>
                <td>
                    <a href="{{ route('admin.topics.edit', $topic) }}" class="btn-app btn-app-edit btn-app-sm">
                        Upravit
                    </a>
                    <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn-app btn-app-danger btn-app-sm">
                            Smazat
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@if ($topics instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4 d-flex justify-content-center">
        {{ $topics->links() }}
    </div>
@endif
@endsection
