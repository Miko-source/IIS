<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Models\CampaignStep;

class CampaignWorkerController extends Controller
{
    /**
     * Výběr témat, kde je možné spravovat pracovníky kampaní
     */
    public function selectTopic()
    {
        $topics = Topic::with('campaigns')->get();
        return view('campaigns.manage', compact('topics'));
    }


    /**
     * Správa pracovníků dané kampaně – výpis přidaných, dostupných i koordinátorů
     */
    public function manageWorkers(Campaign $campaign)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        // všichni aktuální pracovníci
        $assignedUsers = $campaign->workers()->get();
        $assignedUserIds = $assignedUsers->pluck('id')->toArray();

        // správce kampaně
        $managerId = $campaign->user_id;

        // koordinátoři kroků
        $coordinatorIds = $campaign->steps()->pluck('user_id')->filter()->toArray();

        // uživatelé, kteří se NESMÍ zobrazit k přidání
        $excluded = array_filter([
            ...$assignedUserIds,
            $managerId,
            ...$coordinatorIds,
        ]);

        // dostupní uživatelé pro přidání
        $availableUsers = User::whereNotIn('id', $excluded)->get();

        // všichni uživatelé → dropdown pro "správce kampaně"
        $allUsers = User::all();

        // do dropdownu koordinátorů patří všichni KROMĚ správce kampaně
        $coordinators = User::where('id', '!=', $campaign->user_id)->get();

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

        // přidání pracovníka
        $campaign->users()->syncWithoutDetaching([$user->id]);

        // přepočet role
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

        // odebrat z pracovníků
        $campaign->workers()->detach($user->id);

        // přepočítat roli po odebrání
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
