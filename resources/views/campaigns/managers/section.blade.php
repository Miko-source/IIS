
@component('components.panel', ['title' => 'Správce kampaně'])
    @if($campaign->user_id)
        @include('campaigns.managers.display', ['campaign' => $campaign, 'topic' => $topic])
    @elseif(!$campaign->user_id)
        @include('campaigns.managers.empty', ['campaign' => $campaign, 'topic' => $topic])
    @endif

    @if(request()->has('edit_manager'))
        @include('campaigns.managers.form', [
            'campaign' => $campaign, 
            'topic' => $topic,
            'users' => $users
        ])
    @endif
@endcomponent
