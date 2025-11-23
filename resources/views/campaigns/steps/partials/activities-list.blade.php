{{-- resources/views/campaigns/steps/partials/activities-list.blade.php --}}

<h3 class="mt-4">Aktivity</h3>

@can('update', $step)
    <a href="{{ route('activities.create', [$campaign->id, $step->id]) }}"
       class="btn btn-success mb-3">
        + Přidat aktivitu
    </a>
@endcan

@forelse($activities as $activity)
    @include('campaigns.steps.partials.activity-item', [
        'activity' => $activity,
        'campaign' => $campaign,
        'step' => $step
    ])
@empty
    <p class="text-muted fst-italic">Žádné aktivity zatím nejsou.</p>
@endforelse
