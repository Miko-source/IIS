<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\Campaign;    
use App\Models\CampaignStep;     
use App\Models\User;
use App\Services\StepCompletionService;

class CampaignStepController extends Controller
{
    public function __construct(
        private StepCompletionService $completionService
    ) {}

    public function index(Campaign $campaign)
    {
        $this->authorize('viewAny', [CampaignStep::class, $campaign]);

        // Použij scope pro filtrování kroků podle oprávnění
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

        $campaign->steps()->create($validated);

        return redirect()
            ->route('campaign.steps.index', $campaign)
            ->with('success', 'Krok kampaně byl vytvořen.');
    }

    public function show(Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('view', $step);

        $step->load(['activities', 'user:id,name,surname']);

        $previousStep = $campaign->steps()
            ->where('order', '<', $step->order)
            ->orderByDesc('order')
            ->first();

        $coordinators = User::where('id', '!=', $campaign->user_id)
            ->select('id', 'name', 'surname')
            ->get();

        return view('campaigns.steps.show', [
            'campaign'     => $campaign,
            'step'         => $step,
            'activities'   => $step->activities,
            'coordinators' => $coordinators,
            'previousStep' => $previousStep,
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

        $step->update($validated);

        return redirect()
            ->route('campaign.steps.show', [$campaign, $step])
            ->with('success', 'Krok kampaně byl upraven.');
    }

    public function destroy(\App\Models\Campaign $campaign, \App\Models\CampaignStep $step)
    {
        $this->authorize('delete', $step);
        // $step = CampaignStep::findOrFail($stepId);
        // $campaign = $step->campaign;

        $step->delete();

        return redirect()
            ->route('topics.campaigns.show', [$campaign->topic_id, $campaign->id])
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
}