@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Správa pracovníků kampaně</h1>
    <h3 class="text-muted">{{ $campaign->name }}</h3>


    {{-- Flash zpráva --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- Přidání pracovníka --}}
    <div class="card mb-4">
        <div class="card-header">Přidat pracovníka</div>
        <div class="card-body">

            <form action="{{ route('campaigns.workers.add', $campaign->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Vyber uživatele</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} {{ $user->surname }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">Přidat</button>
            </form>

        </div>
    </div>


    {{-- Seznam přiřazených pracovníků --}}
    <div class="card">
        <div class="card-header">Pracovníci v kampani</div>
        <div class="card-body">

            @if($assignedUsers->isEmpty())
                <p class="text-muted">V kampani nejsou přiřazeni žádní pracovníci.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedUsers as $u)
                            <tr>
                                <td>{{ $u->name }} {{ $u->surname }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->role }}</td>
                                <td>
                                    <form action="{{ route('campaigns.workers.remove', [$campaign->id, $u->id]) }}" 
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            Odebrat
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

</div>
@endsection
