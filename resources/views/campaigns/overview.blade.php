<div class="mt-5">

    @foreach ($campaign->steps as $step)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Krok {{ $step->order }}:</strong> {{ $step->name }}
                </div>

         

            {{-- 🔧 DETAIL KROKU --}}
            <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}"
                class="btn btn-sm btn-outline-secondary">
                Detail kroku
            </a>
            </div>

            <ul class="list-group list-group-flush">

                @forelse ($step->activities as $activity)
                    @php
                        $worker = $activity->users->first();

                        if ($activity->messages->count()) {
                            $status = $activity->messages->last()->success ? 'done' : 'fail';
                        } else {
                            $status = 'pending';
                        }

                        $icons = [
                            'done'    => '🟢',
                            'fail'    => '🔴',
                            'pending' => '🟡'
                        ];
                    @endphp

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="me-2">{{ $icons[$status] }}</span>
                            <strong>{{ $activity->name }}</strong>

                            @if($worker)
                                <small class="text-muted ms-2">
                                    — {{ $worker->name }} {{ $worker->surname }}
                                </small>
                            @else
                                <small class="text-muted ms-2">
                                    — nepřiřazen
                                </small>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                              @if(auth()->check())
                                @php
                                $user = auth()->user();
                                @endphp
                                @php
                                    // Má uživatel záznam v activity_user?
                                    $pivot = $activity->workers()->where('user_id', $user->id)->first()?->pivot;
                                @endphp

                                {{-- Uživatel ještě není přihlášen --}}
                                @if(!$pivot)
                                    <form action="{{ route('activities.signup', $activity->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary">Přihlásit se</button>
                                    </form>

                                {{-- Uživatel JE přihlášen --}}
                                @else
                                    <div class="d-flex align-items-center gap-3">

                                        {{-- Tlačítko odhlásit (detach) --}}
                                        <form action="{{ route('activities.leave', $activity->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">Odhlásit se</button>
                                        </form>

                                        {{-- Text o stavu přihlášení --}}
                                        @if($pivot->is_confirmed)
                                            <span class="text-success fw-bold"> Potvrzeno koordinátorem</span>
                                        @else
                                            <span class="text-warning fw-bold"> Čeká se na potvrzení</span>
                                        @endif

                                    </div>
                                @endif

                            @endif


                            {{-- 🔧 UPRAVIT AKTIVITU --}}
                            <a href="{{ route('activities.edit', [$campaign->id, $step->id, $activity->id]) }}"
                               class="btn btn-sm btn-warning">
                                Upravit
                            </a>

                        </div>
                    </li>

                @empty
                    <li class="list-group-item text-muted fst-italic">
                        Žádné aktivity v tomto kroku.
                    </li>
                @endforelse

            </ul>
        </div>
    @endforeach
</div>
