{{-- Zobrazení aktuálního správce --}}
<p class="mb-2">
    <strong>{{ $campaign->manager->name }}</strong> 
    <span class="text-muted">({{ $campaign->manager->email }})</span>
</p>
<div class="d-flex gap-2">
    <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_manager=1" class="btn btn-warning btn-sm">
        Změnit správce
    </a>
    <form method="POST" action="{{ route('topics.campaigns.manager.destroy', [$topic, $campaign]) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Opravdu odebrat správce?')">
            Odebrat správce
        </button>
    </form>
</div>