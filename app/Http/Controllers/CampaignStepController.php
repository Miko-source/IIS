<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
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
        $this->authorize('viewAny', [CampaignStep::class, $campaign]);

        $steps = $campaign->steps()
            ->visibleFor(auth()->user(), $campaign)
            ->with('user:id,name,surname')
            ->orderBy('order')
            ->get();

        return view('campaigns.steps.index', compact('campaign', 'steps'));
    }

    public function create(Campaign $campaign)
    {
        $this->authorize('create', [CampaignStep::class, $campaign]);

        $coordinators = User::where('role', UserRole::COORDINATOR)
            ->select('id', 'name', 'surname')
            ->get();

        return view('campaigns.steps.create', compact('campaign', 'coordinators'));
    }

   public function store(Request $request, Campaign $campaign)
{
    $this->authorize('create', [CampaignStep::class, $campaign]);

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'order'       => ['required', 'integer', 'min:1'],
        'user_id'     => ['required', 'exists:users,id'],
        'description' => ['nullable', 'string'],
    ]);

    $newOrder = $validated['order'];

    // POSUNOUT všechny existující kroky >= newOrder
    CampaignStep::where('campaign_id', $campaign->id)
        ->where('order', '>=', $newOrder)
        ->increment('order');

    // vytvořit nový krok s požadovaným pořadím
    $campaign->steps()->create($validated);

    return redirect()
        ->route('campaign.steps.index', $campaign)
        ->with('success', 'Krok byl vytvořen a pořadí ostatních kroků bylo automaticky upraveno.');
}



    public function show(Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('view', $step);

        $step->load(['activities', 'user:id,name,surname']);

        $previousStep = $campaign->steps()
            ->where('order', '<', $step->order)
            ->orderByDesc('order')
            ->first();
        
        $stepStates = $this->stepStateService->calculateStepStates($campaign);

        $coordinators = User::where('id', '!=', $campaign->user_id)
            ->select('id', 'name', 'surname')
            ->get();

        return view('campaigns.steps.show', [
            'campaign'     => $campaign,
            'step'         => $step,
            'activities'   => $step->activities,
            'coordinators' => $coordinators,
            'previousStep' => $previousStep,
            'canShowComplete' => $stepStates[$step->id]['show_complete'] ?? false,
        ]);
    }

    public function edit(Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('update', $step);

        $coordinators = User::where('role', UserRole::COORDINATOR)
            ->select('id', 'name', 'surname')
            ->get();

        return view('campaigns.steps.edit', compact('campaign', 'step', 'coordinators'));
    }

    public function update(Request $request, Campaign $campaign, CampaignStep $step)
{
    $this->authorize('update', $step);

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'order'       => ['required', 'integer', 'min:1'],
        'user_id'     => ['required', 'exists:users,id'],
        'description' => ['nullable', 'string'],
    ]);

    // DUPLIKÁT PŘI EDITACI?
    $exists = CampaignStep::where('campaign_id', $campaign->id)
        ->where('order', $validated['order'])
        ->where('id', '!=', $step->id)
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['order' => 'Krok s tímto pořadím již existuje.'])
            ->withInput();
    }

    $step->update($validated);

    return redirect()
        ->route('campaign.steps.show', [$campaign, $step])
        ->with('success', 'Krok kampaně byl upraven.');
}


public function destroy(Campaign $campaign, CampaignStep $step)
{
    $this->authorize('delete', $step);

    $deletedOrder = $step->order;

    // smažeme krok
    $step->delete();

    // přečíslování kroků za ním
    CampaignStep::where('campaign_id', $campaign->id)
        ->where('order', '>', $deletedOrder)
        ->decrement('order');

    return redirect()
        ->route('campaign.steps.index', $campaign->id)
        ->with('success', 'Krok byl úspěšně smazán.');
}



    public function markComplete(Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('markComplete', $step);

        $result = $this->completionService->canComplete($step);
        
        if (!$result['can_complete']) {
            return back()->with('error', $result['error']);
        }

        $this->completionService->complete($step);

        return back()->with('success', 'Krok byl označen jako splněný.');
    }

    public function markIncomplete(Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('markComplete', $step);

        if (! $step->is_completed) {
            return back()->with('info', 'Krok již není označen jako splněný.');
        }

        // cannot revert if next steps are completed
        $hasCompletedNextSteps = $campaign->steps()
            ->where('order', '>', $step->order)
            ->where('is_completed', true)
            ->exists();

        if ($hasCompletedNextSteps) {
            return back()->with('error', 'Nelze zrušit dokončení, protože navazující kroky už jsou označeny jako splněné.');
        }

        $step->update(['is_completed' => false]);

        return back()->with('success', 'Krok byl vrácen do rozpracovaného stavu.');
    }
}
