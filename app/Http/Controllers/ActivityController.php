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

        $activity->update($request->all());

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

        // prihlasi se jednou
        if ($activity->workers()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Už jsi k této aktivitě přihlášen.');
        }
        $activity->users()->attach($user->id, ['is_confirmed' => 0]);
      

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

        $activityUser->is_confirmed = 1;
        $activityUser->save();

        return back()->with('success', 'Uživatel byl potvrzen.');
    }

    public function reject(ActivityUser $activityUser)
    {
        $this->authorize('manage', $activityUser);

        $activityUser->is_confirmed = 2;
        $activityUser->save();

        return back()->with('success', 'Uživatel byl odmítnut.');
    }

}
