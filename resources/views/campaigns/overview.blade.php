<div class="mt-5">
    <h3 class="mb-3">Přehled kroků a aktivit</h3>

    @foreach ($campaign->steps as $step)
        <div class="card mb-3">
            <div class="card-header">
                <strong>Krok {{ $step->order }}:</strong> {{ $step->name }}
            </div>

            <ul class="list-group list-group-flush">

                @forelse ($step->activities as $activity)
                    @php
                        $worker = $activity->users->first();
                        $status = null;

                        if ($activity->messages->count()) {
                            $status = $activity->messages->last()->success ? 'done' : 'fail';
                        } else {
                            $status = 'pending';
                        }

                      $icons = [
                            'done'    => '✅',
                            'fail'    => '❌',
                            'pending' => '⏳',
                        ];

                    @endphp

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="me-2">{{ $icons[$status] }}</span>
                            <strong>{{ $activity->name }}</strong>

                        </div>

                        <a href="{{ route('campaign.steps.show', [$campaign->id, $step->id]) }}" 
                           class="btn btn-sm btn-outline-secondary">
                            Detail kroku
                        </a>
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
