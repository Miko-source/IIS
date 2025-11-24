@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('topics.show', $topic) }}" class="btn btn-outline-secondary btn-sm">
            ← Zpět na téma
        </a>
    </div>

    {{-- hlavička --}}
    @include('campaigns.header', ['campaign' => $campaign, 'topic' => $topic])

    @can('manageManager', App\Models\Campaign::class)
        {{-- pohled pro admina --}}
        @include('campaigns.managers.section', [
            'campaign' => $campaign, 
            'topic' => $topic,
            'users' => $users
        ])
    @else
        {{-- ostatní role --}}
        <p class="mb-3">
            <strong>Správce kampaně:</strong>
            @if($campaign->manager)
                {{ $campaign->manager->name }} <span class="text-muted">({{ $campaign->manager->email }})</span>
            @else
                <span class="text-muted fst-italic">nepřiřazen</span>
            @endif
        </p>
    @endcan

    {{-- inline úpravy --}}
    @include('campaigns.crud.section', ['campaign' => $campaign, 'topic' => $topic])
    
    {{-- kroky  --}}
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
