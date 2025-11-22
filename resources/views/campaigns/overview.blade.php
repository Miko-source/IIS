<div class="mt-5">

    @foreach ($campaign->steps as $step)
        <div class="card mb-3">

            {{-- header --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Krok {{ $step->order }}:</strong> {{ $step->name }}

                    {{-- coordinator --}}
                    @if($step->user)
                        <span class="text-muted ms-2">
                            (Koordinátor: {{ $step->user->name }} {{ $step->user->surname }})
                        </span>
                    @else
                        <span class="text-muted ms-2 fst-italic">(bez koordinátora)</span>
                    @endif

                    {{-- step status --}}
                    @if($step->is_completed)
                        <span class="text-success fw-bold ms-3">✓ Krok splněn</span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    @include('components.edit-button', [
                        'href' => route('campaign.steps.show', [
                        $campaign->id,
                        $step->id,
                        'edit' => 1,
                        'back' => 'campaign'
                    ]),
                        'label' => 'Upravit krok',
                        'small' => true
                    ])

                    {{-- detail --}}
                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Detail
                    </a>

                    @php($canShowComplete = $stepStates[$step->id]['show_complete'] ?? false)

                    @can('markComplete', $step)
                        @if($step->is_completed)
                            <form method="POST"
                                  action="{{ route('campaigns.steps.uncomplete', [$campaign->id, $step->id]) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-danger">
                                    ↺ Zrušit dokončení
                                </button>
                            </form>
                        @elseif($canShowComplete)
                            <form method="POST"
                                  action="{{ route('campaigns.steps.complete', [$campaign->id, $step->id]) }}">
                                @csrf
                                @method('PATCH')

                                <button class="btn btn-sm btn-success">
                                    ✓ Označit jako splněný
                                </button>
                            </form>
                        @endif
                    @endcan

                </div>
            </div>

        </div>
    @endforeach

</div>
