{{-- resources/views/campaigns/steps/partials/activity-item.blade.php --}}

<div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 bg-white shadow-sm">

    {{-- LEFT SIDE – name + dates --}}
    <div>

        <strong>{{ $activity->name }}</strong><br>
         @if($activity->completed)
                <span class="text-success">Dokončeno</span>
            @else
                <span class="text-muted">Probíhá</span>
            @endif
        <small class="text-muted">
            {{ $activity->start_date ? $activity->start_date->format('d. m. Y') : '—' }}
            –
            {{ $activity->end_date ? $activity->end_date->format('d. m. Y') : 'konec neznámý' }}
        </small>
        

    </div>

    {{-- RIGHT SIDE – buttons --}}
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('activities.show', [$campaign->id, $step->id, $activity->id]) }}"
            class="btn btn-sm btn-outline-primary">
                Detail
            </a>
            @can('manageWorkers', $activity)
            <a href="{{ route('activities.workers.index', $activity) }}"
            class="btn btn-sm btn-outline-secondary">
                Spravovat realizátory
            </a>
        @endcan



        @can('update', $activity)
            {{-- Edit --}}
            @include('components.edit-button', [
                'href' => route('activities.edit', [$campaign->id, $step->id, $activity->id]),
                'small' => true
            ])
        @endcan

        @can('delete', $activity)
            {{-- Delete --}}
            @include('components.delete-button', [
                'action' => route('activities.destroy', [$campaign->id, $step->id, $activity->id]),
                'label' => 'Smazat',
                'confirm' => 'Opravdu chcete aktivitu smazat?',
                'small' => true
            ])
        @endcan

        {{-- Sign up / leave --}}
        @if(auth()->check())
            @include('campaigns.steps.partials.activity-signup-button', [
                'activity' => $activity
            ])
        @endif

    </div>

</div>
