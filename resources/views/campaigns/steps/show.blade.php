@extends('layouts.app')

@section('content')
<div class="container">

    <div>
        @include('components.back-link', [
            'target' => route('topics.campaigns.show', [$campaign->topic, $campaign]),
            'label' => '← Zpět na kampaň'
        ])
    </div>

    {{-- Základní informace o kroku --}}
    <div class="mb-4">
        <h2 class="mb-2">{{ $step->order }}. {{ $step->name }}</h2>
        <p class="mb-2"><strong>Pořadí:</strong> {{ $step->order }}</p>

        <p class="mb-2">
            <strong>Stav:</strong>
            @if($step->is_completed)
                <span class="text-success fw-bold">Dokončeno</span>
            @elseif($step->isCompletedSuccessfully())
                <span class="text-warning fw-bold">Čeká na potvrzení / dokončení</span>
            @else
                <span class="text-muted">Probíhá</span>
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

        @include('campaigns.steps.partials.coordinator-panel', [
            'step' => $step,
            'coordinators' => $coordinators,
            'campaign' => $campaign
        ])

        <div class="d-flex gap-2 flex-wrap mb-3">
            <button type="button"
                    class="btn btn-warning btn-sm rounded-pill"
                    id="toggle-step-edit"
                    onclick="
                        document.getElementById('step-edit-form').classList.toggle('d-none');
                    ">
                Upravit krok
            </button>

            {{-- Smazat krok --}}
            @include('components.delete-button', [
                'action' => route('campaign.steps.destroy', [$campaign->id, $step->id]),
                'label' => 'Smazat krok',
                'confirm' => 'Opravdu chcete krok smazat?',
                'small' => true
            ])
        </div>

        @include('campaigns.steps.partials.edit-form', [
            'step' => $step,
            'coordinators' => $coordinators,
            'campaign' => $campaign
        ])
    </div>

    {{-- Seznam aktivit --}}
    @include('campaigns.steps.partials.activities-list', [
        'activities' => $activities,
        'campaign' => $campaign,
        'step' => $step
    ])

</div>
@endsection
