<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Models\CampaignStep;
use App\Enums\UserRole;

class CampaignWorkerController extends Controller
{
    /**
     * Výběr témat, kde je možné spravovat pracovníky kampaní
     */
    public function selectTopic()
    {
        $user = auth()->user();

        // ADMIN 
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            $topics = Topic::with('campaigns')->get();
        }

        // 
        else {
            $topics = Topic::with(['campaigns' => function ($q) use ($user) {
                    // 
                    $q->where('user_id', $user->id);
                }])
                ->whereHas('campaigns', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->get();
        }

        return view('campaigns.manage', compact('topics'));
    }

    /**
     * Správa pracovníků dané kampaně – výpis přidaných, dostupných i koordinátorů
     */
   public function manageWorkers(Campaign $campaign)
{
    $this->authorize('manageWorkers', [Campaign::class, $campaign]);

    // Eager load, ať nemáme N+1 dotazy
    $campaign->load([
        'manager',
        'steps.user',                 // koordinátoři kroků
        'steps.activities.users',     // pracovníci na aktivitách
        'workers',                    // pracovníci kampaně (pivot)
    ]);

    // 1) ID pracovníků z pivot tabulky campaign_user
    $workerIds = $campaign->workers->pluck('id');

    // 2) ID správce kampaně
    $managerId = $campaign->user_id;

    // 3) ID koordinátorů kroků (step->user_id)
    $coordinatorIds = $campaign->steps
        ->pluck('user_id')
        ->filter(); // vyhodí null

    // 4) ID uživatelů z aktivit (ti, co jsou přihlášení k aktivitám dané kampaně)
    $activityUserIds = $campaign->steps
        ->flatMap(function ($step) {
            return $step->activities->flatMap(function ($activity) {
                // pokud chceš jen potvrzené, můžeš filtrovat pivot:
                return $activity->users->pluck('id');
            });
        });

    // 5) Spojit vše dohromady + odstranit duplicity
    $allAssignedIds = collect()
        ->merge($workerIds)
        ->when($managerId, fn ($c) => $c->push($managerId))
        ->merge($coordinatorIds)
        ->merge($activityUserIds)
        ->unique()
        ->values();

    // 6) Skutečný seznam uživatelů do tabulky „Pracovníci v kampani“
    $assignedUsers = User::whereIn('id', $allAssignedIds)
        ->orderBy('surname')
        ->orderBy('name')
        ->get();

    // 7) Uživatelé, kteří se NESMÍ nabízet k přidání (už tam nějak figurují)
    $excluded = $allAssignedIds->all();   // pole ID, které už někde v kampani jsou

    // 8) Dostupní uživatelé pro přidání (select „Přidat pracovníka“)
    $availableUsers = User::whereNotIn('id', $excluded)
        ->orderBy('surname')
        ->orderBy('name')
        ->get();

    // 9) Všichni uživatelé – pro dropdown správce kampaně
    $allUsers = User::orderBy('surname')->orderBy('name')->get();

    // 10) Dropdown pro koordinátory kroků – všichni kromě správce dané kampaně
    $coordinators = User::where('id', '!=', $campaign->user_id)
        ->orderBy('surname')
        ->orderBy('name')
        ->get();

    return view('campaigns.workers', compact(
        'campaign',
        'assignedUsers',
        'availableUsers',
        'coordinators',
        'allUsers'
    ));
}



    /**
     * Přidání pracovníka do kampaně
     */
    public function addWorker(Request $request, Campaign $campaign)
{
    $user = User::findOrFail($request->user_id);

    // přidání pracovníka – pivot campaign_user
    $campaign->workers()->syncWithoutDetaching([$user->id]);

    $user->refreshRole();

    return back()->with('success', 'Pracovník přidán.');
}


    /**
     * Odebrání pracovníka + případné odstranění z role správce a koordinátora
     */
    public function removeWorker(Campaign $campaign, User $user)
{
    $this->authorize('manageWorkers', [Campaign::class, $campaign]);

    // pokud byl správce -> odebrat
    if ($campaign->user_id === $user->id) {
        $campaign->update(['user_id' => null]);
    }

    // pokud byl koordinátor -> odebrat
    $campaign->steps()
        ->where('user_id', $user->id)
        ->update(['user_id' => null]);

    // odebrat z pracovníků (pivot campaign_user)
    $campaign->workers()->detach($user->id);

    $user->refreshRole();

    return back()->with('success', 'Pracovník byl odebrán.');
}





    /**
     * Nastavení koordinátora kroku
     */
    public function updateCoordinator(Request $request, Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $new = $request->user_id;
        $old = $step->user_id;

        // uložit nového koordinátora
        $step->update(['user_id' => $new ?: null]);

        // starý koordinátor:
        if ($old && $old != $new) {

            $oldUser = User::find($old);

            // odebrání z kampaně, pokud není jiný koordinátor
            $stillCoordinator = $campaign->steps()
                ->where('user_id', $old)
                ->exists();

            if (!$stillCoordinator) {
                $campaign->workers()->detach($old);
            }

            // přepočet role
            $oldUser->refreshRole();
        }

        // nový koordinátor musí být pracovník
        if ($new) {
            $campaign->workers()->syncWithoutDetaching([$new]);

            $newUser = User::find($new);
            $newUser->refreshRole();
        }

        return back()->with('success', 'Koordinátor kroku aktualizován.');
    }




    /**
     * Nastavení správce kampaně
     */
   public function updateManager(Request $request, Campaign $campaign)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $new = $request->user_id;
        $old = $campaign->user_id;

        // uložit nového správce
        $campaign->update(['user_id' => $new]);

        // starý správce
        if ($old && $old != $new) {

            $oldUser = User::find($old);

            // odebrat ze všech kroků
            $campaign->steps()->where('user_id', $old)->update(['user_id' => null]);

            // odebrat z pracovníků
            $campaign->workers()->detach($old);

            // přepočet role po odebrání
            $oldUser->refreshRole();
        }

        // nový správce
        if ($new) {
            $campaign->workers()->syncWithoutDetaching([$new]);

            $newUser = User::find($new);
            $newUser->refreshRole();
        }

        return back()->with('success', 'Správce kampaně aktualizován.');
    }


}
