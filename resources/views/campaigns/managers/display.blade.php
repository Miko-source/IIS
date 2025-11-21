{{-- Zobrazení aktuálního správce --}}
<p class="mb-2">
    <strong>{{ $campaign->manager->name }}</strong> 
    <span class="text-muted">({{ $campaign->manager->email }})</span>
</p>
<div class="d-flex gap-2">
    @include('components.ghost-button', [
        'small' => true,
        'label' => 'Změnit správce',
        'href' => route('topics.campaigns.show', [$topic, $campaign]) . '?edit_manager=1'
    ])
    <form method="POST" action="{{ route('topics.campaigns.manager.destroy', [$topic, $campaign]) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Opravdu odebrat správce?')">
            Odebrat správce
        </button>
    </form>
</div>
