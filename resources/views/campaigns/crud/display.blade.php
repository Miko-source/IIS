{{-- Zobrazení akčních tlačítek pro kampaň --}}
<div class="d-flex gap-2 my-3">
    <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_campaign=1" class="btn btn-primary">
        Upravit kampaň
    </a>
    @can('delete', $campaign)
        <form action="{{ route('topics.campaigns.destroy', [$topic, $campaign]) }}" method="POST" onsubmit="return confirm('Opravdu chcete kampaň smazat?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Smazat kampaň</button>
        </form>
    @endcan
</div>
