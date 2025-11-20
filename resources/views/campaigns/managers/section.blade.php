{{-- Hlavní sekce pro správu správce kampaně --}}
<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title">Správce kampaně</h5>
        
        @if($campaign->user_id && !request()->has('edit_manager'))
            {{-- Zobrazení aktuálního správce --}}
            @include('campaigns.managers.display', ['campaign' => $campaign, 'topic' => $topic])
        @elseif(!$campaign->user_id && !request()->has('edit_manager'))
            {{-- Žádný správce přiřazen --}}
            @include('campaigns.managers.empty', ['campaign' => $campaign, 'topic' => $topic])
        @endif

        @if(request()->has('edit_manager'))
            {{-- Formulář pro změnu/přiřazení správce --}}
            @include('campaigns.managers.form', [
                'campaign' => $campaign, 
                'topic' => $topic,
                'users' => $users
            ])
        @endif
    </div>
</div>