{{-- State when no manager is assigned --}}
<p class="text-muted mb-2">Žádný správce přiřazen</p>
@can('manageManager', App\Models\Campaign::class)
    <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_manager=1" class="btn btn-success btn-sm">
        Přiřadit správce
    </a>
@endcan
