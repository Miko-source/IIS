<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */
namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Campaign;
use App\Models\User;
use App\Enums\UserRole;
use App\Services\StepStateService;
use Illuminate\Support\Facades\Log;


/*
 * Kontroler pro spravu kampani
 * Obsahuje vytvoreni, zobrazeni, editaci a mazani kampane
 */

class CampaignController extends Controller
{
    public function __construct(
        private StepStateService $stepStateService // servis pro vypocet stavu kroku
    ) {}

    public function create(Topic $topic)
    {
        // autorizace tvorby kampane
        $this->authorize('create', Campaign::class);

        // vrati formular pro zalozeni kampane
        return view('campaigns.create', compact('topic'));
    }

    public function store(Request $request, Topic $topic)
    {
        // kontrola opravneni
        $this->authorize('create', Campaign::class);

        // validace vstupu
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // vytvoreni nove kampane v ramci tematu
        $campaign = $topic->campaigns()->create([
            ...$validated,
            'user_id' => $request->user()->id, // autor/spravce kampane
        ]);

        // presmerovani na detail kampane
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Kampan byla vytvorena.');
    }

    public function show(Topic $topic, Campaign $campaign)
    {
        // kontrola opravneni zobrazit kampa
        $this->authorize('view', $campaign);

        // nacteni seznamu uzivatelu pro mozne prirazeni
        $users = User::where('role', '!=', UserRole::ADMIN)
            ->select('id', 'name', 'surname', 'email')
            ->get();

        // prednacteni kroku a prirazeneho koordinatora + posledni zpravy aktivit
        $campaign->load([
            'steps' => function ($query) {
                $query->with([
                    'user:id,name,surname',
                    'activities.latestMessage',
                ])->orderBy('order');
            },
        ]);

        // zobrazeni detailu kampane
        return view('campaigns.show', [
            'topic' => $topic,
            'campaign' => $campaign,
            'users' => $users,
            'stepStates' => $this->stepStateService->calculateStepStates($campaign), // vypocet stavu kroku
        ]);
    }

    public function edit(Topic $topic, Campaign $campaign)
    {
        // kontrola opravneni upravovat kampa
        $this->authorize('update', $campaign);

        // editace probiha na show strance, pouze se aktivuje rezim editace
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign, 'edit_campaign' => 1]);
    }

    public function update(Request $request, Topic $topic, Campaign $campaign)
    {
        // kontrola opravneni
        $this->authorize('update', $campaign);

        // validace dat pri editaci
        $validated = $request->validateWithBag('campaignEdit', [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // ulozeni zmen
        $campaign->update($validated);

        // presmerovani zpet do edit modulu
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign, 'edit_campaign' => 1])
            ->with('success', 'Kampan byla upravena.');
    }

    public function destroy(Topic $topic, Campaign $campaign)
    {
        // kontrola opravneni smazat kampa
        $this->authorize('delete', $campaign);

        // smazani kampane
        $campaign->delete();

        // presmerovani zpet na detail tematu
        return redirect()
            ->route('topics.show', $topic)
            ->with('success', 'Kampan byla smazana.');
    }
}
