{{-- resources/views/steps/partials/coordinator-panel.blade.php --}}

@component('components.panel', ['title' => 'Koordinátor kroku'])
    <p class="mb-2">
        @if($step->user)
            <strong>{{ $step->user->name }} {{ $step->user->surname }}</strong>
        @else
            <span class="text-muted fst-italic">není přiřazen</span>
        @endif
    </p>

    <div class="d-flex gap-2 flex-wrap">
        @include('components.ghost-button', [
            'small' => true,
            'label' => 'Změnit koordinátora',
            'onclick' => "document.getElementById('koordinator-form').classList.toggle('d-none')"
        ])

        {{-- Odebrat koordinátora --}}
        <button type="button"
                id="remove-coordinator-{{ $step->id }}"
                class="btn btn-danger btn-sm">
            Odebrat koordinátora
        </button>
    </div>
    
    {{-- Formulář pro změnu koordinátora (skrytý, zobrazuje se po kliknutí) --}}
    <div id="koordinator-form" class="d-none mt-3">
        <form id="coordinator-form-{{ $step->id }}"
              method="POST"
              action="{{ route('campaigns.steps.coordinator.update', [$campaign->id, $step->id]) }}">
            @csrf
            @method('PATCH')

            <div class="mb-3 d-flex gap-2 align-items-center flex-wrap">
                <select name="user_id" class="form-select w-auto">
                    <option value="">— bez koordinátora —</option>

                    @foreach($coordinators as $user)
                        <option value="{{ $user->id }}"
                            @if($step->user_id == $user->id) selected @endif>
                            {{ $user->name }} {{ $user->surname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    Uložit
                </button>
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        onclick="
                            document.getElementById('koordinator-form').classList.add('d-none');
                        ">
                    Zrušit
                </button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('coordinator-form-{{ $step->id }}');
            if (!form) return;

            form.addEventListener('submit', (e) => {
                const select = form.querySelector('[name="user_id"]');
                if (select && select.value === '') {
                    const ok = confirm('Chcete krok ponechat bez koordinátora?');
                    if (!ok) {
                        e.preventDefault();
                    }
                }
            });

            const removeBtn = document.getElementById('remove-coordinator-{{ $step->id }}');
            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    const select = form.querySelector('[name="user_id"]');
                    if (select) {
                        select.value = '';
                    }

                    const ok = confirm('Chcete krok ponechat bez koordinátora?');
                    if (!ok) {
                        return;
                    }

                    if (form.requestSubmit) {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });
            }
        });
    </script>
@endcomponent
