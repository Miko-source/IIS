<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;    
use App\Models\CampaignStep;     
use App\Models\User;
use App\Services\StepCompletionService;
use App\Services\StepStateService;

class CampaignStepController extends Controller
{
    public function __construct(
        private StepCompletionService $completionService,
        private StepStateService $stepStateService
    ) {}

    public function index(Campaign $campaign)
    {
        // kontrola opravneni pro zobrazeni seznamu kroku
        $this->authorize('viewAny', [CampaignStep::class, $campaign]);

        // nacteni kroku viditelnych pro uzivatele
        $steps = $campaign->steps()
            ->visibleFor(auth()->user(), $campaign)
            ->with('user:id,name,surname')
            ->orderBy('order')
            ->get();

        // zobrazeni view se vsemi kroky
        return view('campaigns.steps.index', compact('campaign', 'steps'));
    }

    public function create(Campaign $campaign)
    {
        // kontrola opravneni pro vytvoreni kroku
        $this->authorize('create', [CampaignStep::class, $campaign]);

        // nacteni dostupnych koordinatoru, krome spravce kampane
        $coordinators = User::where('id', '!=', $campaign->user_id)
            ->orderBy('surname')
            ->orderBy('name')
            ->select('id', 'name', 'surname', 'email')
            ->get();

        // zobrazeni formulare
        return view('campaigns.steps.create', compact('campaign', 'coordinators'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        // opravneni vytvorit krok
        $this->authorize('create', [CampaignStep::class, $campaign]);

        // zakladni kontrola hodnoty poradi
        if ($request->order <= 0) {
            return back()->with('error', 'Poradi kroku musi byt kladne cislo vetsi nez 0.');
        }

        // validace vstupu
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'order'       => ['required', 'integer', 'min:1'],
            'user_id'     => ['required', 'exists:users,id'],
            'description' => ['nullable', 'string'],
        ]);

        $newOrder = $validated['order'];

        // posun vsech kroku na stejnem nebo vetsim poradi
        CampaignStep::where('campaign_id', $campaign->id)
            ->where('order', '>=', $newOrder)
            ->increment('order');

        // vytvoreni noveho kroku
        $campaign->steps()->create($validated);

        return redirect()
            ->route('campaign.steps.index', $campaign)
            ->with('success', 'Krok byl vytvoren a poradi ostatnich kroku bylo upraveno.');
    }

    public function show(Campaign $campaign, CampaignStep $step)
    {
        // kontrola opravneni pro zobrazeni detailu
        $this->authorize('view', $step);

        // nacteni aktivit a koordinatora
        $step->load([
            'activities',
            'user:id,name,surname,email'
        ]);

        // nalezeni predchoziho kroku
        $previousStep = $campaign->steps()
            ->where('order', '<', $step->order)
            ->orderByDesc('order')
            ->first();

        // vypocet stavu kroku
        $stepStates = $this->stepStateService->calculateStepStates($campaign);

        // dostupni koordinatori
        $coordinators = User::where('id', '!=', $campaign->user_id)
            ->select('id', 'name', 'surname', 'email')
            ->get();

        // zobrazeni detailu kroku
        return view('campaigns.steps.show', [
            'campaign'        => $campaign,
            'step'            => $step,
            'activities'      => $step->activities,
            'coordinators'    => $coordinators,
            'previousStep'    => $previousStep,
            'canShowComplete' => $stepStates[$step->id]['show_complete'] ?? false,
        ]);
    }

    public function edit(Campaign $campaign, CampaignStep $step)
    {
        // opravneni upravit krok
        $this->authorize('update', $step);

        // zobrazeni formulare pro editaci
        return view('campaigns.steps.edit', compact('campaign', 'step'));
    }

    public function update(Request $request, Campaign $campaign, CampaignStep $step)
    {
        // zakladni kontrola hodnoty poradi
        if ($request->order <= 0) {
            return back()->with('error', 'Poradi kroku musi byt kladne cislo vetsi nez 0.');
        }

        // validace vstupu
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order'       => ['required', 'integer', 'min:1'],
        ]);

        $newOrder = $validated['order'];
        $oldOrder = $step->order;

        // pokud se poradi nemeni, jen ulozime zmeny
        if ($newOrder == $oldOrder) {
            $step->update($validated);
            return back()->with('success', 'Krok byl upraven.');
        }

        // zjistime, zda existuje jiny krok se stejnym poradim
        $existingStep = $campaign->steps()
            ->where('order', $newOrder)
            ->where('id', '!=', $step->id)
            ->first();

        if ($existingStep) {
            // pokud existuje, prohodime poradi obou kroku
            $existingStep->update(['order' => $oldOrder]);
            $step->update($validated);
        } else {
            // pokud neexistuje, jen ulozime nove poradi
            $step->update($validated);
        }

        return back()->with('success', 'Poradi kroku bylo aktualizovano.');
    }

    public function destroy(Campaign $campaign, CampaignStep $step)
    {
        // kontrola opravneni smazat krok
        $this->authorize('delete', $step);

        $deletedOrder = $step->order;

        // smazani kroku
        $step->delete();

        // posunuti kroku za nim o -1
        CampaignStep::where('campaign_id', $campaign->id)
            ->where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()
            ->route('campaign.steps.index', $campaign->id)
            ->with('success', 'Krok byl uspesne smazan.');
    }

    public function markComplete(Campaign $campaign, CampaignStep $step)
    {
        // opravneni oznacit krok jako splneny
        $this->authorize('markComplete', $step);

        // kontrola, zda je krok mozne dokoncit
        $result = $this->completionService->canComplete($step);
        
        if (!$result['can_complete']) {
            return back()->with('error', $result['error']);
        }

        // oznaceni kroku jako splneny
        $this->completionService->complete($step);

        return back()->with('success', 'Krok byl oznacen jako splneny.');
    }

    public function markIncomplete(Campaign $campaign, CampaignStep $step)
    {
        // opravneni zrusit dokonceni kroku
        $this->authorize('markComplete', $step);

        // kontrola, zda krok uz neni nedokoncen
        if (!$step->is_completed) {
            return back()->with('info', 'Krok jiz neni oznacen jako splneny.');
        }

        // nelze zrusit dokonceni, pokud dalsi kroky jsou splnene
        $hasCompletedNextSteps = $campaign->steps()
            ->where('order', '>', $step->order)
            ->where('is_completed', true)
            ->exists();

        if ($hasCompletedNextSteps) {
            return back()->with('error', 'Nelze zrusit dokonceni, protoze dalsi kroky jsou splnene.');
        }

        // nastaveni jako nedokoncene
        $step->update(['is_completed' => false]);

        return back()->with('success', 'Krok byl vracen do rozpracovaneho stavu.');
    }
}
