<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;    
use App\Models\CampaignStep;     
use App\Models\User; 

class CampaignStepController extends Controller
{
    public function index(Campaign $campaign)
    {
        $steps = $campaign->steps()->orderBy('order')->get();
        return view('campaigns.steps.index', compact('campaign', 'steps'));
    }

    public function create(Campaign $campaign)
    {
        $coordinators = User::where('role', 'coordinator')->get();
        return view('campaigns.steps.create', compact('campaign', 'coordinators'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'order'       => 'required|integer|min:1',
            'user_id'     => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        CampaignStep::create([
            'campaign_id' => $campaign->id,
            'name'        => $request->name,
            'order'       => $request->order,
            'user_id'     => $request->user_id,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('campaign.steps.index', $campaign->id)
            ->with('success', 'Krok kampaně byl vytvořen.');
    }

    public function show(Campaign $campaign, CampaignStep $step)
    {
        $activities = $step->activities;

        // seznam možných koordinátorů
        // můžeš omezit podle rolí nebo používat všichni
        $coordinators = \App\Models\User::where('id', '!=', $campaign->user_id)->get();

        $previousStep = $campaign->steps()
            ->where('order', '<', $step->order)
            ->orderByDesc('order')
            ->first();

        return view('campaigns.steps.show', [
            'campaign'     => $campaign,
            'step'         => $step,
            'activities'   => $activities,
            'coordinators' => $coordinators,
            'previousStep' => $previousStep,
        ]);
    }

    public function edit(Campaign $campaign, CampaignStep $step)
    {
        $coordinators = User::where('role', 'coordinator')->get();

        return view('campaigns.steps.edit', compact('campaign', 'step', 'coordinators'));
    }

    public function update(Request $request, Campaign $campaign, CampaignStep $step)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'order'       => 'required|integer|min:1',
            'user_id'     => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $step->update($request->only(['name', 'order', 'user_id', 'description']));

        return redirect()
            ->route('campaign.steps.show', [$campaign->id, $step->id])
            ->with('success', 'Krok kampaně byl upraven.');
    }

    public function destroy(Campaign $campaign, CampaignStep $step)
    {
        $user = auth()->user();

        if (
            !$user->hasRoleOrHigher(\App\Enums\UserRole::ADMIN) &&
            $campaign->user_id !== $user->id &&
            $step->user_id !== $user->id
        ) {
            abort(403);
        }

        $step->delete();

        return redirect()
            ->route('campaign.steps.index', $campaign->id)
            ->with('success', 'Krok byl smazán.');
    }


    public function markComplete(Campaign $campaign, CampaignStep $step)
    {
        $user = auth()->user();

        // kontrola roli
        if (
            !$user->hasRoleOrHigher(\App\Enums\UserRole::ADMIN) &&
            $campaign->user_id !== $user->id &&
            $step->user_id !== $user->id
        ) {
            abort(403);
        }

        // předchozí kroky musí být dokončené
        $hasUnfinishedPrevious = $campaign->steps()
            ->where('order', '<', $step->order)
            ->where(function ($q) {
                $q->whereNull('is_completed')->orWhere('is_completed', false);
            })
            ->exists();

        if ($hasUnfinishedPrevious) {
            return back()->with('error', 'Neplatné pořadí: nejprve dokončete předchozí kroky.');
        }

        foreach ($step->activities as $activity) {
            $lastMessage = $activity->messages()->latest()->first();
            
            // atleast one message must exist
            if (!$lastMessage) {
                return back()->with('error', 
                    "Aktivita '{$activity->name}' ještě nemá žádnou zprávu. Všechny aktivity musí být vyhodnoceny.");
            }
            
            // Zpráva musí existovat; úspěch i neúspěch jsou akceptovány, null znamená nepodáno
            if ($lastMessage->success === null) {
                return back()->with('error', 
                    "Aktivita '{$activity->name}' nemá vyhodnocení. Všechny aktivity musí mít podanou zprávu.");
            }
        }

        // Pokud krok nemá žádné aktivity, nelze jej označit jako splněný
        if ($step->activities->count() === 0) {
            return back()->with('error', 'Krok nemá žádné aktivity. Nelze jej označit jako splněný.');
        }

        // aktualizace stavu kroku
        $step->update(['is_completed' => true]);

        return back()->with('success', 'Krok byl označen jako splněný.');
    }
}
