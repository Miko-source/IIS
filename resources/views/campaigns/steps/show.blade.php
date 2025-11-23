@extends('layouts.app')

@section('content')
<div class="container">

    <div>
        @include('components.back-link', [
            'target' => route('topics.campaigns.show', [$campaign->topic, $campaign]),
            'label' => '← Zpět na kampaň'
        ])
    </div>

    {{-- step info --}}
    <div class="mb-4">
        <h2 class="mb-2">{{ $step->order }}. {{ $step->name }}</h2>
        <p class="mb-2"><strong>Pořadí:</strong> {{ $step->order }}</p>

        <p class="mb-2">
            <strong>Stav:</strong>
            @if($step->is_completed)
                <span class="text-success fw-bold">Dokončeno</span>
            @elseif($step->isCompletedSuccessfully())
                <span class="text-muted fw-bold">Čeká na dokončení předchozího kroku</span>
            @else
                <span class="text-warning">Probíhá</span>
            @endif
        </p>

        @if(!empty($previousStep))
            <p class="mb-2">
                <strong>Navazuje na krok:</strong>
                {{ $previousStep->order }}. {{ $previousStep->name }}
            </p>
        @endif

        <p class="mb-2">
            <strong>Popis:</strong>
            {{ $step->description ?: '—' }}
        </p>

        @can('update', $campaign)
            @include('campaigns.steps.partials.coordinator-panel', [
                'step' => $step,
                'coordinators' => $coordinators,
                'campaign' => $campaign
            ])
        @else
            <p class="mb-3">
                <strong>Koordinátor kroku:</strong>
                @if($step->user)
                    {{ $step->user->name }} {{ $step->user->surname }}
                @else
                    <span class="text-muted fst-italic">není přiřazen</span>
                @endif
            </p>
        @endcan

        @can('update', $step)
            <div class="d-flex gap-2 flex-wrap mb-3">
                <button type="button"
                        class="btn btn-warning btn-sm rounded-pill"
                        id="toggle-step-edit"
                        onclick="
                            document.getElementById('step-edit-form').classList.toggle('d-none');
                        ">
                    Upravit krok
                </button>

                {{-- delete step --}}
                @can('delete', $step)
                    @include('components.delete-button', [
                        'action' => route('campaign.steps.destroy', [$campaign->id, $step->id]),
                        'label' => 'Smazat krok',
                        'confirm' => 'Opravdu chcete krok smazat?',
                        'small' => true
                    ])
                @endcan
            </div>

            @include('campaigns.steps.partials.edit-form', [
                'step' => $step,
                'coordinators' => $coordinators,
                'campaign' => $campaign
            ])
        @endcan
    </div>

    {{-- activities list --}}
    @include('campaigns.steps.partials.activities-list', [
        'activities' => $activities,
        'campaign' => $campaign,
        'step' => $step
    ])

</div>
@endsection
