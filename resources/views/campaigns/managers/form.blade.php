{{-- Formulář pro změnu nebo přiřazení správce --}}
<div class="border-top pt-3 mt-3">
    @if($campaign->user_id)
        <h6 class="mb-3">Aktuální správce: <strong>{{ $campaign->manager->name }}</strong></h6>
        <h6 class="mb-3">Změnit správce</h6>
    @else
        <h6 class="mb-3">Přiřadit správce</h6>
    @endif

    <form method="POST" action="{{ route('topics.campaigns.manager.update', [$topic, $campaign]) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="user_id" class="form-label">
                {{ $campaign->user_id ? 'Nový správce' : 'Správce kampaně' }}
            </label>
            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                <option value="">-- Vyberte uživatele --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                {{ $campaign->user_id ? 'Změnit správce' : 'Přiřadit správce' }}
            </button>
            <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="btn btn-secondary">
                Zrušit
            </a>
        </div>
    </form>
</div>