<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Type;
use App\Models\Campaign;
use App\Models\CampaignStep;
use Illuminate\Http\Request;
use App\Models\ActivityUser;

class ActivityController extends Controller
{
public function show(Campaign $campaign, CampaignStep $step, Activity $activity)
{
    // bezpečnost – kontrola, že to k sobě patří
    if ($activity->step_id !== $step->id || $step->campaign_id !== $campaign->id) {
        abort(404);
    }


    $activity->load(['step.campaign', 'users', 'messages']);



    return view('activities.show', compact('campaign', 'step', 'activity'));
}



    public function create(Campaign $campaign, CampaignStep $step)
    {
        $types = Type::all();

        return view('activities.create', compact('campaign', 'step', 'types'));
    }

    public function store(Request $request, Campaign $campaign, CampaignStep $step)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        Activity::create([
            'name' => $request->name,
            'type_id' => $request->type_id,
            'cost' => $request->cost,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'step_id' => $step->id,
        ]);

        return redirect()
            ->route('campaign.steps.show', [$campaign->id, $step->id])
            ->with('success', 'Aktivita byla úspěšně přidána.');
    }

    public function edit(Campaign $campaign, CampaignStep $step, Activity $activity)
    {
        $types = Type::all();

        return view('activities.edit', compact('campaign', 'step', 'activity', 'types'));
    }

    public function update(Request $request, Campaign $campaign, CampaignStep $step, Activity $activity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $activity->update([
            'name' => $request->name,
            'type_id' => $request->type_id,
            'cost' => $request->cost,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('campaign.steps.show', [$campaign->id, $step->id])
            ->with('success', 'Aktivita byla upravena.');
    }

    public function destroy(Campaign $campaign, CampaignStep $step, Activity $activity)
    {
        $activity->delete();

        return redirect()
            ->route('campaign.steps.show', [$campaign->id, $step->id])
            ->with('success', 'Aktivita byla smazána.');
    }

public function signup(Activity $activity)
{
    $user = auth()->user();

    // Nelze se přihlásit ke kroku, který je hotový
    if ($activity->step->is_completed) {
        return back()->with('error', 'Tento krok je již dokončen. Nelze se přihlásit.');
    }

    // Najdeme existující pivot pro uživatele
    $pivot = $activity->users()
        ->where('user_id', $user->id)
        ->first();

    // === 1) Uživatel byl dříve odmítnut -> znovu pending ===
    if ($pivot && $pivot->pivot->is_confirmed == 2) {

        $activity->users()->updateExistingPivot($user->id, [
            'is_confirmed' => 0,
            'is_completed' => 0,
        ]);

        // Přihlášením (pending) se aktivita MUSÍ otevřít
        if ($activity->completed) {
            $activity->completed = false;
            $activity->save();
        }

        return back()->with('success', 'Přihlášení obnoveno, čeká se na potvrzení.');
    }

    // === 2) Pokud existuje pending nebo approved -> přihlášen už je ===
    if ($pivot) {
        return back()->with('error', 'Už jsi k této aktivitě přihlášen.');
    }

    // === 3) Nové přihlášení ===
    $activity->users()->attach($user->id, [
        'is_confirmed' => 0,
        'is_completed' => 0,
    ]);

    // Nové pending přihlášení musí aktivitu otevřít
    $activity->recalculateCompletion();


    return back()->with('success', 'Úspěšně jsi se přihlásil, čeká se na potvrzení koordinátora.');
}

    public function leave(Activity $activity)
    {
        $userId = auth()->id();

        // Zda je worker přihlášen k aktivitě
        if (! $activity->users()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Nejsi u této aktivity přihlášen.');
        }

        // Smazání pivot záznamu → tím zmizí i jeho stav (pending/confirmed/rejected)
        $activity->users()->detach($userId);

        return back()->with('success', 'Byl jsi odhlášen z aktivity.');
    }


    public function confirm(ActivityUser $activityUser)
    {
        $this->authorize('manage', $activityUser);
        $activity = $activityUser->activity;

        $activityUser->is_confirmed = 1;
        $activityUser->save();

        $activity->recalculateCompletion();      
        

        return back()->with('success', 'Uživatel byl potvrzen.');
    }

    public function reject(ActivityUser $activityUser)
    {
        $this->authorize('manage', $activityUser);

        $activityUser->is_confirmed = 2;
        $activityUser->save();

        return back()->with('success', 'Uživatel byl odmítnut.');
    }

    public function confirm_activity($id)
    {
        $activity = Activity::findOrFail($id);

        // role kontrola
        $role = auth()->user()->role->value;
        if (!in_array($role, ['admin','campaign_manager','coordinator'])) {
            abort(403);
        }

        if (!$activity->allWorkersCompleted()) {
            return back()->with('error', 'Aktivitu nelze potvrdit — chybí zprávy od realizátorů.');
        }

        $activity->completed = true;
        $activity->save();

        return back()->with('success', 'Aktivita byla úspěšně potvrzena.');
    }


}
