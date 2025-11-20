@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Přehled kampaní</h1>

    @if($campaignsByTopic->isEmpty())
        <p class="text-muted">Nemáte žádné kampaně k zobrazení.</p>
    @else
        @foreach($campaignsByTopic as $topicCampaigns)
            @php
                $topic = $topicCampaigns->first()->topic;
            @endphp

            <div class="mt-4">
                <h3>{{ $topic->name }}</h3>

                <ul>
                    @foreach($topicCampaigns as $campaign)
                        <li>
                            <a href="{{ route('dashboard.campaigns.show', $campaign) }}">
                                {{ $campaign->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endif
</div>
@endsection
