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

                    {{-- Detail kroku --}}
                    <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Detail
                    </a>

                    {{-- TLAČÍTKO OZNAČIT JAKO SPLNĚNÝ --}}
                    @php
                        // OPRAVENÁ LOGIKA - kontrola všech aktivit (stačí mít podanou zprávu; success může být 1 nebo 0)
                        $allDone = true;
                        $hasActivities = $step->activities->count() > 0;
                        
                        if (!$hasActivities) {
                            $allDone = false;
                        } else {
                            foreach ($step->activities as $activity) {
                                $lastMessage = $activity->messages()->latest()->first();
                                
                                // Pokud aktivita nemá zprávu nebo zpráva nemá vyhodnocení (success === null)
                                if (!$lastMessage || $lastMessage->success === null) {
                                    $allDone = false;
                                    break;
                                }
                            }
                        }

                        // kdo může označit krok jako splněný
                        $canMark =
                            auth()->user()->hasRoleOrHigher(\App\Enums\UserRole::ADMIN) ||
                            $campaign->user_id === auth()->id() ||
                            $step->user_id === auth()->id();

                        // Kontrola, že všechny předchozí kroky jsou dokončené
                        $previousCompleted = $campaign->steps
                            ->where('order', '<', $step->order)
                            ->every(function($s) {
                                return (bool) $s->is_completed;
                            });
                    @endphp

                    {{-- pokud je krok dokončený, NEZOBRAZUJE se tlačítko --}}
                    @if($allDone && $canMark && !$step->is_completed && $previousCompleted)
                        <form method="POST"
                              action="{{ route('campaigns.steps.complete', [$campaign->id, $step->id]) }}">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-sm btn-success">
                                ✓ Označit jako splněný
                            </button>
                        </form>
                    @endif

                </div>
            </div>

        </div>
    @endforeach

</div>
