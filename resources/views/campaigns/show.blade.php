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

    @can('manageManager', App\Models\Campaign::class)
        {{--admin view --}}
        @include('campaigns.managers.section', [
            'campaign' => $campaign, 
            'topic' => $topic,
            'users' => $users
        ])
    @else
        {{--rest of the roles view --}}
        <p class="mb-3">
            <strong>Správce kampaně:</strong>
            @if($campaign->manager)
                {{ $campaign->manager->name }} <span class="text-muted">({{ $campaign->manager->email }})</span>
            @else
                <span class="text-muted fst-italic">nepřiřazen</span>
            @endif
        </p>
    @endcan

    {{-- inline edit --}}
    @include('campaigns.crud.section', ['campaign' => $campaign, 'topic' => $topic])
    
    {{-- steps  --}}
    @can('update', $campaign)
        <div class="d-flex flex-wrap gap-2 my-3">
            <a href="{{ route('campaign.steps.index', $campaign->id) }}" class="btn-app btn-app-primary">
                Spravovat kroky kampaně
            </a>
        </div>
    @endcan

    @include('campaigns.overview', [
        'campaign' => $campaign,
        'stepStates' => $stepStates ?? []
    ])
</div>
@endsection
