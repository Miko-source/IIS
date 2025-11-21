{{-- resources/views/campaigns/steps/partials/activity-item.blade.php --}}

<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 bg-white shadow-sm">

    {{-- LEVÁ STRANA – název + datum --}}
    <div>
        <strong>{{ $activity->name }}</strong><br>
        <small class="text-muted">
            {{ $activity->start_date ?? '—' }}
            –
            {{ $activity->end_date ?? 'konec neznámý' }}
        </small>
    </div>

    {{-- PRAVÁ STRANA – tlačítka --}}
    <div class="d-flex gap-2 align-items-center">

        {{-- Upravit --}}
        @include('components.edit-button', [
            'href' => route('activities.edit', [$campaign->id, $step->id, $activity->id]),
            'small' => true
        ])

        {{-- Smazat --}}
        @include('components.delete-button', [
            'action' => route('activities.destroy', [$campaign->id, $step->id, $activity->id]),
            'label' => 'Smazat',
            'confirm' => 'Opravdu chcete aktivitu smazat?',
            'small' => true
        ])

        {{-- Přihlášení / odhlášení --}}
        @if(auth()->check())
            @include('campaigns.steps.partials.activity-signup-button', [
                'activity' => $activity
            ])
        @endif

    </div>

</div>
