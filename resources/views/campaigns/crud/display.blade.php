{{-- kampan tlacitka --}}
<div class="d-flex gap-2 my-3">
    @include('components.edit-button', [
        'href' => route('topics.campaigns.show', [$topic, $campaign]) . '?edit_campaign=1',
        'label' => 'Upravit kampaň'
    ])
    @can('delete', $campaign)
        <form action="{{ route('topics.campaigns.destroy', [$topic, $campaign]) }}" method="POST" onsubmit="return confirm('Opravdu chcete kampaň smazat?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Smazat kampaň</button>
        </form>
    @endcan
</div>
