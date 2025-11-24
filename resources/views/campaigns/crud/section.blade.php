{{-- sekce CRUD akce kampane --}}
@can('update', $campaign)
    @if(request()->has('edit_campaign'))
        @include('campaigns.crud.edit', ['campaign' => $campaign, 'topic' => $topic])
    @else
        @include('campaigns.crud.display', ['campaign' => $campaign, 'topic' => $topic])
    @endif
@endcan
