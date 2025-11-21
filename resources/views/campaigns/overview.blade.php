<div class="mt-5">

    @foreach ($campaign->steps as $step)
        <div class="card mb-3">

            {{-- Hlavička kroku --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Krok {{ $step->order }}:</strong> {{ $step->name }}

                    {{-- Koordinátor --}}
                    @if($step->user)
                        <span class="text-muted ms-2">
                            (Koordinátor: {{ $step->user->name }} {{ $step->user->surname }})
                        </span>
                    @else
                        <span class="text-muted ms-2 fst-italic">(bez koordinátora)</span>
                    @endif

                    {{-- stav kroku --}}
                    @if($step->is_completed)
                        <span class="text-success fw-bold ms-3">✔ Krok splněn</span>
                    @endif
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('campaign.steps.edit', [
                            $campaign->id,
                            $step->id,
                            'back' => 'campaign'
                        ]) }}"
                    class="btn btn-sm btn-warning">
                        Upravit krok
                    </a>

                    

                    {{-- Detail kroku --}}
                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Detail
                    </a>

                    {{-- TLAČÍTKO OZNAČIT JAKO SPLNĚNÝ --}}
                    @php
                        // všechny aktivity mají poslední message success = 1
                        $allDone = $step->activities->every(function($activity) {
                            if ($activity->messages->count()) {
                                return $activity->messages->last()->success === 1;
                            }
                            return false;
                        });

                        // kdo může označit krok jako splněný
                        $canMark =
                            auth()->user()->hasRoleOrHigher(\App\Enums\UserRole::ADMIN) ||
                            $campaign->user_id === auth()->id() ||
                            $step->user_id === auth()->id();
                    @endphp

                    {{-- pokud je krok dokončený, NEZOBRAZUJE se tlačítko --}}
                    @if($allDone && $canMark && !$step->is_completed)
                        <form method="POST"
                              action="{{ route('campaigns.steps.complete', [$campaign->id, $step->id]) }}">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-sm btn-success">
                                ✔ Označit jako splněný
                            </button>
                        </form>
                    @endif

                </div>
            </div>

        </div>
    @endforeach

</div>
