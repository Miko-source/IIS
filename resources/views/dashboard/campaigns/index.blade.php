@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Přehled kampaní</h1>

    @forelse($campaignsByTopic as $topicId => $campaigns)

        @php
            $topic = $campaigns->first()->topic;
            $topicCollapseId = 'topic_'.$topicId;
        @endphp

        {{-- ======= TEMA ======= --}}
        <div class="mb-3">

            <button class="btn btn-outline-primary w-100 text-start"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $topicCollapseId }}">
                <strong>{{ $topic->name }}</strong>
            </button>

            <div id="{{ $topicCollapseId }}" class="collapse mt-3">

                {{-- ======= Kampane pod tematem======= --}}
                @foreach($campaigns as $campaign)

                    @php
                        $campaignCollapseId = 'campaign_'.$campaign->id;
                    @endphp

                    {{-- ======= KAMPAN ======= --}}
                    <div class="border rounded mb-3 p-3 bg-white">

                        <button class="btn btn-outline-dark w-100 text-start"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $campaignCollapseId }}">
                            <strong>{{ $campaign->name }}</strong>
                        </button>

                        <div id="{{ $campaignCollapseId }}" class="collapse mt-3">

                            {{-- ======= KROKY KAMPANĚ ======= --}}
                            @foreach($campaign->steps as $step)

                                @php
                                    $stepCompleted = $step->is_completed ?? false;
                                @endphp

                                <div class="mb-2 p-2 rounded {{ $stepCompleted ? 'bg-success bg-opacity-25' : '' }}">
                                    <strong>
                                        Krok {{ $step->order }}: {{ $step->name }}
                                        @if($stepCompleted)
                                            <span class="text-success ms-2 fw-bold">✔ splněno</span>
                                        @endif
                                    </strong>

                                    {{-- Aktivity --}}
                                    <ul class="mt-1">
                                        @forelse($step->activities as $activity)

                                            @php
                                                $now = now();

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

                                            <li>
                                                <strong>{{ $activity->name }}</strong>
                                                <span class="ms-2">{{ $icons[$status] }}</span>
                                            </li>

                                        @empty
                                            <li class="text-muted fst-italic">Žádné aktivity</li>
                                        @endforelse
                                    </ul>

                                </div>

                            @endforeach

                            <a href="{{ route('topics.campaigns.show', [
                                $topic,
                                $campaign,
                                'back' => 'campaigns'
                            ]) }}">
                                Detail kampaně
                            </a>





                            {{-- SCHOVAT KROKY / KAMPAŇ --}}
                            <button class="btn btn-outline-secondary btn-sm mt-2"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $campaignCollapseId }}">
                                Schovat
                            </button>

                        </div>
                    </div>

                @endforeach

                {{-- SCHOVAT TÉMA --}}
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
@endsection
