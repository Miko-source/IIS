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

    {{-- Akce / inline edit kampaně --}}
    @include('campaigns.crud.section', ['campaign' => $campaign, 'topic' => $topic])
    
    {{-- kroky  --}}
    <div class="d-flex flex-wrap gap-2 my-3">
        <a href="{{ route('campaign.steps.index', $campaign->id) }}" class="btn-app btn-app-primary">
            Spravovat kroky kampaně
        </a>
        <a href="{{ route('topics.show', $topic) }}" class="btn-app btn-app-secondary">
            ← Zpět na téma
        </a>
    </div>
       @php
    $back = request('back');
@endphp

@if($back === 'campaigns')
    <a href="{{ url('/dashboard/campaigns') }}" class="btn btn-secondary mb-3">
        ← Zpět na přehled kampaní
    </a>

@elseif($back === 'topic')
    <a href="{{ route('topics.show', $campaign->topic) }}" class="btn btn-secondary mb-3">
        ← Zpět na detail tématu
    </a>

@else
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        ← Zpět
    </a>
@endif



    @include('campaigns.overview', [
        'campaign' => $campaign
    ])
</div>
@endsection
