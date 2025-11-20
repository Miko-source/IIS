{{-- Stav když není přiřazen žádný správce --}}
<p class="text-muted mb-2">Žádný správce přiřazen</p>
<a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}?edit_manager=1" class="btn btn-success btn-sm">
    Přiřadit správce
</a>