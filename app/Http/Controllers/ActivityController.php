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
    // kontrola opravneni pro zobrazeni aktivity
    $this->authorize('view', $activity);

    // kontrola ze aktivita patri do daneho kroku a kampane
    if ($activity->step_id !== $step->id || $step->campaign_id !== $campaign->id) {
        abort(404);
    }

    // nacteni potrebnych vztahu pro zobrazeni detailu
    $activity->load(['step.campaign', 'users', 'messages']);

    return view('activities.show', compact('campaign', 'step', 'activity'));
}

    public function create(Campaign $campaign, CampaignStep $step)
    {
        // nacteni typu aktivit pro formular
        $types = Type::all();

        return view('activities.create', compact('campaign', 'step', 'types'));
    }

    public function store(Request $request, Campaign $campaign, CampaignStep $step)
    {
        // validace vstupu pro novou aktivitu
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        // vytvoreni nove aktivity
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
            ->with('success', 'Aktivita byla uspesne pridana.');
    }

    public function edit(Campaign $campaign, CampaignStep $step, Activity $activity)
    {
        // nacteni typu pro editacni formular
        $types = Type::all();

        return view('activities.edit', compact('campaign', 'step', 'activity', 'types'));
    }

    public function update(Request $request, Campaign $campaign, CampaignStep $step, Activity $activity)
    {
        // validace upravene aktivity
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        // aktualizace dat aktivity
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
        // kontrola opravneni
        $this->authorize('delete', $activity);

        // kontrola vazby aktivity na dany krok a kampan
        if ($activity->step_id !== $step->id || $step->campaign_id !== $campaign->id) {
            abort(404);
        }

        // smazani aktivity
        $activity->delete();

        return redirect()
            ->route('campaign.steps.show', [$campaign->id, $step->id])
            ->with('success', 'Aktivita byla smazana.');
    }

public function signup(Activity $activity)
{
    $user = auth()->user();

    // pokud je krok dokoncen, nelze se prihlasit
    if ($activity->step->is_completed) {
        return back()->with('error', 'Tento krok je jiz dokonceny.');
    }

    // zjisti jestli uzivatel ma zaznam v pivotu
    $pivot = $activity->users()
        ->where('user_id', $user->id)
        ->first();

    // pokud byl predtim odmitnut -> povolit znovu cekajici stav
    if ($pivot && $pivot->pivot->is_confirmed == 2) {

        $activity->users()->updateExistingPivot($user->id, [
            'is_confirmed' => 0,
            'is_completed' => 0,
        ]);

        // pokud by byla aktivita jako celek oznacena jako dokoncena, resetovat
        if ($activity->completed) {
            $activity->completed = false;
            $activity->save();
        }

        return back()->with('success', 'Prihlaseni obnoveno.');
    }

    // uzivatel uz je registrovan
    if ($pivot) {
        return back()->with('error', 'Uz jsi prihlasen.');
    }

    // nove prihlaseni uzivatele
    $activity->users()->attach($user->id, [
        'is_confirmed' => 0,
        'is_completed' => 0,
    ]);

    // prepocti dokoncenost aktivity
    $activity->recalculateCompletion();

    return back()->with('success', 'Prihlaseni odeslano, ceka se na potvrzeni.');
}

    public function leave(Activity $activity)
    {
        // kontrola ze uzivatel je u aktivity
        $userId = auth()->id();

        if (!$activity->users()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Nejsi prihlasen.');
        }

        // odhlaseni uzivatele z aktivity
        $activity->users()->detach($userId);

        return back()->with('success', 'Byl jsi odhlasen.');
    }

    public function confirm(ActivityUser $activityUser)
    {
        // kontrola opravneni pro potvrzeni realizatora
        $this->authorize('manage', $activityUser);
        $activity = $activityUser->activity;

        // potvrzeni uzivatele
        $activityUser->is_confirmed = 1;
        $activityUser->save();

        // prepocti stav aktivity
        $activity->recalculateCompletion();      

        return back()->with('success', 'Uzivatel byl potvrzen.');
    }

    public function reject(ActivityUser $activityUser)
    {
        // kontrola opravneni
        $this->authorize('manage', $activityUser);

        // nastaveni stavu odmitnuto
        $activityUser->is_confirmed = 2;
        $activityUser->save();

        return back()->with('success', 'Uzivatel byl odmitnut.');
    }

    public function confirm_activity($id)
    {
        // najdi aktivitu
        $activity = Activity::findOrFail($id);

        // kontrola opravneni pro potvrzeni aktivity
        $role = auth()->user()->role->value;
        if (!in_array($role, ['admin','campaign_manager','coordinator'])) {
            abort(403);
        }

        // kontrola ze vsichni realizatori odevzdali zpravu
        if (!$activity->allWorkersCompleted()) {
            return back()->with('error', 'Chybi zpravy od realizatoru.');
        }

        // oznac aktivitu jako dokoncena
        $activity->completed = true;
        $activity->save();

        return back()->with('success', 'Aktivita byla potvrzena.');
    }

 public function addWorker(Request $request, Activity $activity)
{
    // kontrola opravneni na prirazeni pracovnika
    $this->authorize('assignWorker', $activity);

    // validace uzivatele
    $data = $request->validate([
        'user_id' => ['required', 'exists:users,id'],
    ]);

    $userId = $data['user_id'];
    $campaign = $activity->step->campaign;

    // pokud uzivatel neni v kampani -> automaticky ho pridat
    $isInCampaign = $campaign->workers()
        ->where('users.id', $userId)
        ->exists();

    if (!$isInCampaign) {
        $campaign->workers()->attach($userId);
    }

    // kontrola ze uz neni prirazen
    $alreadyAssigned = $activity->users()
        ->where('users.id', $userId)
        ->exists();

    if ($alreadyAssigned) {
        return back()->with('error', 'Uzivatel je jiz prirazen.');
    }

    // prirazeni uzivatele k aktivite
    $activity->users()->attach($userId, [
        'is_confirmed' => 1,
        'is_completed' => 0,
    ]);

    return back()->with('success', 'Uzivatel pridan k aktivite a kampani.');
}

}
