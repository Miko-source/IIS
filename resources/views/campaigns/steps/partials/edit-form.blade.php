{{-- resources/views/steps/partials/edit-form.blade.php --}}

<div id="step-edit-form" class="{{ request()->boolean('edit') ? '' : 'd-none' }} mt-3">
    <form method="POST"
          action="{{ route('campaign.steps.update', [$campaign->id, $step->id]) }}"
          class="border rounded p-3 bg-white shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Název kroku</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $step->name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pořadí</label>
            <input type="number"
                   name="order"
                   class="form-control"
                   value="{{ old('order', $step->order) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Koordinátor</label>
            <select name="user_id" class="form-select" required>
                @foreach($coordinators as $coord)
                    <option value="{{ $coord->id }}"
                        @selected($coord->id == $step->user_id)>
                        {{ $coord->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Popis</label>
            <textarea name="description" class="form-control">{{ old('description', $step->description) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Uložit změny</button>
            <button type="button" class="btn btn-outline-secondary btn-sm"
                    onclick="
                        document.getElementById('step-edit-form').classList.add('d-none');
                        const toggleBtn = document.getElementById('toggle-step-edit');
                        if (toggleBtn) toggleBtn.classList.remove('d-none');
                    ">
                Zavřít
            </button>
        </div>
    </form>
</div>