@php
    $user = auth()->user();

    // stránkování témat
    $topics = \App\Models\Topic::where(function ($q) use ($user) {
        if ($user->isAdmin()) return;
        if ($user->isCampaignManager()) {
            $q->whereHas('campaigns', fn($c) => $c->where('user_id', $user->id));
        } else {
            $q->whereHas('campaigns.users', fn($c) => $c->where('campaign_user.user_id', $user->id));
        }
    })->paginate(5);
@endphp

<div class="bg-light border-end" style="width: 260px; min-height: 100vh;">
    <div class="p-3">
        
        <h5 class="mb-3">📌 Rychlá navigace</h5>

        <a href="{{ route('dashboard') }}" class="d-block mb-2">🏠 Dashboard</a>

        <hr>

        {{-- Témata --}}
        @foreach($topics as $topic)
            <div class="mb-2">
                <strong>{{ $topic->name }}</strong>

                {{-- KAMPAŇE limit 5 --}}
                @foreach($topic->campaigns->take(5) as $campaign)
                    @if($user->can('view', $campaign))
                        
                        <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="d-block">
                            📣 {{ $campaign->name }}
                        </a>

                        {{-- KROKY limit 5 --}}
                        @foreach($campaign->steps->take(5) as $step)
                            @if($user->can('view', $step))
                                <div class="ms-3">

                                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}" class="d-block">
                                        🧩 {{ $step->name }}
                                    </a>

                                    {{-- AKTIVITYlimit  5 --}}
                                    @foreach($step->activities->take(5) as $activity)
                                        @if($user->can('view', $activity))
                                            <a href="{{ route('activities.show', [
                                                'campaign' => $campaign->id,
                                                'step' => $step->id,
                                                'activity' => $activity->id
                                            ]) }}" 
                                               class="d-block ms-3 small">
                                                🔧 {{ $activity->name }}
                                            </a>
                                        @endif
                                    @endforeach

                                </div>
                            @endif
                        @endforeach

                    @endif
                @endforeach

            </div>
        @endforeach

        {{-- stránkovací navigace--}}
        <div class="mt-3 small">
            {{ $topics->links() }}
        </div>

    </div>
</div>
