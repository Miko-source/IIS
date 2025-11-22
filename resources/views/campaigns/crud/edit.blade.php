{{-- Formulář pro úpravu kampaně (inline použitelný v show) --}}
<div class="border rounded p-3">
    <h6 class="mb-3">Upravit kampaň</h6>

    @php
        $useOld = $errors->any();
        
        $nameValue = $useOld ? old('name') : $campaign->name;
        
        if ($useOld) {
            $startDateHidden = old('start_date');
            
            try {
                $startDateDisplay = $startDateHidden 
                    ? \Carbon\Carbon::createFromFormat('Y-m-d', $startDateHidden)->format('d/m/Y') 
                    : '';
            } catch (\Exception $e) {
                $startDateDisplay = ''; 
            }
        } else {
            $startDateHidden = $campaign->start_date;
            $startDateDisplay = $campaign->start_date 
                ? \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') 
                : '';
        }

        if ($useOld) {
            $endDateHidden = old('end_date');
            try {
                $endDateDisplay = $endDateHidden 
                    ? \Carbon\Carbon::createFromFormat('Y-m-d', $endDateHidden)->format('d/m/Y') 
                    : '';
            } catch (\Exception $e) {
                $endDateDisplay = '';
            }
        } else {
            $endDateHidden = $campaign->end_date;
            $endDateDisplay = $campaign->end_date 
                ? \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') 
                : '';
        }
    @endphp

    <form method="POST" action="{{ route('topics.campaigns.update', [$topic, $campaign]) }}" id="campaignEditForm" novalidate>
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="campaign_edit_name" class="form-label">Název kampaně <span class="text-danger">*</span></label>
            <input
                type="text"
                id="campaign_edit_name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ $nameValue }}"
                required
                autocomplete="off"
            >
            <div id="campaign_edit_name_error" class="text-danger small" style="display: none;"></div>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Datum začátku --}}
        <div class="mb-3">
            <label for="campaign_edit_start_date_display" class="form-label">Datum začátku <span class="text-danger">*</span></label>
            <input
                type="text"
                id="campaign_edit_start_date_display"
                class="form-control @error('start_date') is-invalid @enderror"
                placeholder="DD/MM/RRRR"
                value="{{ $startDateDisplay }}"
                required
                autocomplete="off"
            >
            <input 
                type="hidden" 
                id="campaign_edit_start_date" 
                name="start_date" 
                value="{{ $startDateHidden }}"
            >
            <div class="form-text">Formát DD/MM/RRRR (den/měsíc/rok).</div>
            <div id="campaign_edit_start_date_error" class="text-danger small" style="display: none;"></div>
            @error('start_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="campaign_edit_end_date_display" class="form-label">Datum konce</label>
            <input
                type="text"
                id="campaign_edit_end_date_display"
                class="form-control @error('end_date') is-invalid @enderror"
                placeholder="DD/MM/RRRR"
                value="{{ $endDateDisplay }}"
                autocomplete="off"
            >
            <input 
                type="hidden" 
                id="campaign_edit_end_date" 
                name="end_date" 
                value="{{ $endDateHidden }}"
            >
            <div id="campaign_edit_end_date_error" class="text-danger small" style="display: none;"></div>
            @error('end_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <p class="text-muted small mb-3">
            <span class="text-danger">*</span> Povinné pole
        </p>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Uložit změny</button>
            <a href="{{ route('topics.campaigns.show', [$topic, $campaign]) }}" class="btn btn-secondary btn-sm">
                Zrušit
            </a>
        </div>
    </form>
</div>

@include('campaigns.partials.form-scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initCampaignForm === 'function') {
            initCampaignForm({
                formId: 'campaignEditForm',
                nameId: 'campaign_edit_name',
                nameErrorId: 'campaign_edit_name_error',
                startDisplayId: 'campaign_edit_start_date_display',
                startHiddenId: 'campaign_edit_start_date',
                startErrorId: 'campaign_edit_start_date_error',
                endDisplayId: 'campaign_edit_end_date_display',
                endHiddenId: 'campaign_edit_end_date',
                endErrorId: 'campaign_edit_end_date_error',
            });
        } else {
            console.error('Funkce initCampaignForm nebyla nalezena. Zkontrolujte načtení partials/form-scripts.');
        }
    });
</script>