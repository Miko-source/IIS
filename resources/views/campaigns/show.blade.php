@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('topics.show', $topic) }}" class="btn btn-outline-secondary btn-sm">
            ← Zpět na téma
        </a>
    </div>

    {{-- header --}}
    @include('campaigns.header', ['campaign' => $campaign, 'topic' => $topic])

    {{-- managing campaign managers - only for admins --}}
    @can('manageManager', App\Models\Campaign::class)
        @include('campaigns.managers.section', [
            'campaign' => $campaign, 
            'topic' => $topic,
            'users' => $users
        ])
        
    @endcan

    {{-- inline edit --}}
    @include('campaigns.crud.section', ['campaign' => $campaign, 'topic' => $topic])
    
    {{-- steps  --}}
    <div class="d-flex flex-wrap gap-2 my-3">
        <a href="{{ route('campaign.steps.index', $campaign->id) }}" class="btn-app btn-app-primary">
            Spravovat kroky kampaně
        </a>
    </div>



    @include('campaigns.overview', [
        'campaign' => $campaign,
        'stepStates' => $stepStates ?? []
    ])
</div>
@endsection
