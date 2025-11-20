@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kampaň: {{ $campaign->name }}</h1>
    <p class="text-muted">
        Téma: {{ $campaign->topic->name }}
    </p>

    @if($campaign->steps->isEmpty())
        <p class="text-muted">Tato kampaň zatím nemá žádné kroky.</p>
    @else
        @foreach($campaign->steps as $step)
            <div class="card mt-4">
                <div class="card-body">
                    <h3>{{ $step->name }}</h3>
                    @php
                        $allDone = $step->isCompletedSuccessfully();
                        $allowed = auth()->user()->hasRoleOrHigher(\App\Enums\UserRole::ADMIN)
                                || auth()->id() === $campaign->user_id;
                    @endphp

                    @if($allowed && $allDone)
                        <form action="{{ route('dashboard.steps.delete', $step) }}" method="post"
                            onsubmit="return confirm('Opravdu chcete odstranit tento krok?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm mb-3">
                                Odebrat krok
                            </button>
                        </form>
                    @endif

                    @if($step->activities->isEmpty())
                        <p class="text-muted">V tomto kroku nejsou žádné aktivity.</p>
                    @else
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Aktivita</th>
                                    <th>Stav</th>
                                    <th>Poslední zpráva</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($step->activities as $activity)
                                    @php
                                        $message = $activity->messages->sortByDesc('created_at')->first();

                                        if (!$message) {
                                            $statusLabel = 'Bez vyhodnocení';
                                            $statusClass = 'bg-secondary';
                                        } elseif ($message->success) {
                                            $statusLabel = 'Splněná';
                                            $statusClass = 'bg-success';
                                        } else {
                                            $statusLabel = 'Nesplněná';
                                            $statusClass = 'bg-danger';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $activity->name }}</td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($message)
                                                <strong>Pracovník:</strong>
                                                {{ $message->user->name ?? 'Neznámý' }}
                                                <br>
                                                <strong>Datum:</strong>
                                                {{ $message->created_at->format('d.m.Y H:i') }}
                                                <br>
                                                <strong>Zpráva:</strong>
                                                {{ $message->content }}
                                            @else
                                                <span class="text-muted">Zatím nebyla podána žádná zpráva.</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
