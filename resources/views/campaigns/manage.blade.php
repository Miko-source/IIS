@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Správa pracovníků v kampaních</h1>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">
        ← Zpět na dashboard
    </a>

    @foreach($topics as $topic)
        <div class="card mb-4">
            <div class="card-header">
                <strong>{{ $topic->name }}</strong>
            </div>

            <div class="card-body">
                @if($topic->campaigns->isEmpty())
                    <p class="text-muted">Toto téma nemá žádné kampaně.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kampaň</th>
                                <th>Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topic->campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->name }}</td>
                                    <td>
                                        <a href="{{ route('campaigns.workers', $campaign->id) }}"
                                           class="btn btn-primary btn-sm">
                                            Spravovat pracovníky
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach

</div>
@endsection
