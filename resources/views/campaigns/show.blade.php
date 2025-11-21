@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('topics.show', $topic) }}" class="btn btn-outline-secondary btn-sm">
            ← Zpět na téma
        </a>
    </div>

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

    {{-- Akce / inline edit kampaně --}}
    @include('campaigns.crud.section', ['campaign' => $campaign, 'topic' => $topic])
    
    {{-- kroky  --}}
    <div class="d-flex flex-wrap gap-2 my-3">
        <a href="{{ route('campaign.steps.index', $campaign->id) }}" class="btn-app btn-app-primary">
            Spravovat kroky kampaně
        </a>
    </div>



    @include('campaigns.overview', [
        'campaign' => $campaign
    ])
</div>
@endsection
