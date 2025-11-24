@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Přehled témat a kampaní</h1>

    {{-- WRAPPER --}}
    <div id="topicsAccordion">
        @forelse($campaignsByTopic as $topicId => $campaigns)

            @php
                $topic = $campaigns->first()->topic;
                $topicCollapseId = 'topic_'.$topicId;
            @endphp

            <div class="mb-3">

                {{-- TÉMA --}}
                <div class="d-flex align-items-center mb-1">
                    <button class="btn btn-outline-primary w-100 text-start me-2"
                            data-bs-toggle="collapse"
                            data-bs-target="#{{ $topicCollapseId }}">
                        <strong>{{ $topic->name }}</strong>
                    </button>

                    <a href="{{ route('topics.show', $topic) }}" class="btn btn-info btn-sm">
                        Detail
                    </a>
                </div>
                <div id="{{ $topicCollapseId }}" 
                     class="collapse mt-3"
                     data-bs-parent="#topicsAccordion">

                    @foreach($campaigns as $campaign)

                        @php
                            $campaignCollapseId = 'campaign_'.$campaign->id;
                        @endphp

                        {{-- KAMPAŇ --}}
                        <div class="border rounded mb-3 p-3 bg-white">

                            <div class="d-flex align-items-center mb-1">
                                <button class="btn btn-outline-dark w-100 text-start me-2"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $campaignCollapseId }}">
                                    <strong>{{ $campaign->name }}</strong>
                                </button>

                                <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" 
                                   class="btn btn-info btn-sm">
                                    Detail
                                </a>
                            </div>

                            <div id="{{ $campaignCollapseId }}" class="collapse mt-3">

                                {{-- KROKY --}}
                                @foreach($campaign->steps as $step)
                                    @php
                                        $stepCompleted = $step->is_completed ?? false;
                                    @endphp

                                    <div class="mb-2 p-2 rounded {{ $stepCompleted ? 'bg-success bg-opacity-25' : '' }}">

                                        <div class="d-flex align-items-center">
                                            <strong class="me-2">
                                                Krok {{ $step->order }}: {{ $step->name }}
                                                @if($stepCompleted)
                                                    <span class="text-success ms-2 fw-bold">✔ splněno</span>
                                                @endif
                                            </strong>

                                            <a href="{{ route('campaign.steps.show', [$campaign, $step]) }}" 
                                               class="btn btn-info btn-sm ms-auto">
                                                Detail
                                            </a>
                                        </div>

                                        {{-- AKTIVITY --}}
                                        <ul class="mt-1">
                                            @forelse($step->activities as $activity)
                                                @php
                                                    if ($activity->completed) {
                                                        $status = 'done';
                                                    } else {
                                                        $status = 'in_progress';
                                                    }

                                                    $icons = [
                                                        'done'        => '✔️ splněno',
                                                        'failed'      => '❌ selhalo',
                                                        'not_started' => '⚪ ještě nezačalo',
                                                        'in_progress' => '⏳ probíhá',
                                                        'overdue'     => '🔥 po termínu',
                                                    ];
                                                @endphp

                                                <li class="d-flex align-items-center">
                                                    <span class="me-2">
                                                        <strong>{{ $activity->name }}</strong>
                                                        {{ $icons[$status] }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="text-muted fst-italic">Žádné aktivity</li>
                                            @endforelse
                                        </ul>

                                    </div>
                                @endforeach

                                <button class="btn btn-outline-secondary btn-sm mt-2"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $campaignCollapseId }}">
                                    Schovat
                                </button>

                            </div>
                        </div>

                    @endforeach

                    <button class="btn btn-outline-secondary btn-sm mt-2"
                            data-bs-toggle="collapse"
                            data-bs-target="#{{ $topicCollapseId }}">
                        Schovat téma
                    </button>

                </div>
            </div>

        @empty
            <p>Žádné kampaně k zobrazení.</p>
        @endforelse
    </div>
</div>
@endsection
