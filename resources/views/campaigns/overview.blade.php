<div class="mt-5">

    @foreach ($campaign->steps as $step)
        <div class="card mb-3">

            {{-- Hlavička kroku --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Krok {{ $step->order }}:</strong> {{ $step->name }}

                        @if($step->user)
                            <span class="text-muted ms-2">
                                (Koordinátor: {{ $step->user->name }} {{ $step->user->surname }})
                            </span>
                        @else
                            <span class="text-muted ms-2 fst-italic">(bez koordinátora)</span>
                        @endif

                </div>

                <div class="d-flex gap-2">

                    {{-- Upravit krok --}}
                    <a href="{{ route('campaign.steps.edit', [$campaign->id, $step->id]) }}"
                       class="btn btn-sm btn-warning">
                        Upravit krok
                    </a>


                    {{-- Detail kroku --}}
                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Detail
                    </a>

                </div>
            </div>


        </div>
    @endforeach
</div>
