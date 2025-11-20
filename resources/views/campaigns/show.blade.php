@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Hlavička kampaně --}}
    @include('campaigns.header', ['campaign' => $campaign, 'topic' => $topic])

    {{-- Sekce pro správu správce (pouze pro adminy) --}}
    @can('manageManager', App\Models\Campaign::class)
        @include('campaigns.managers.section', [
            'campaign' => $campaign, 
            'topic' => $topic,
            'users' => $users
        ])
    @endcan

    @can('update', $campaign)
        <div class="d-flex gap-2 my-3">
            <a href="{{ route('topics.campaigns.edit', [$topic, $campaign]) }}" class="btn btn-primary">
                Upravit kampaň
            </a>
            <form action="{{ route('topics.campaigns.destroy', [$topic, $campaign]) }}" method="POST" onsubmit="return confirm('Opravdu chcete kampaň smazat?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Smazat kampaň</button>
            </form>
        </div>
    @endcan
    {{-- CRUD akce pro kampaň (inline) --}}
    <div class="d-flex flex-wrap gap-2 my-3">
        <a href="{{ route('campaign.steps.index', $campaign->id) }}" class="btn btn-outline-primary">
            Spravovat kroky kampaně
        </a>
        <a href="{{ route('topics.show', $topic) }}" class="btn btn-secondary">
            ← Zpět na téma
        </a>
    </div>
</div>
@endsection
