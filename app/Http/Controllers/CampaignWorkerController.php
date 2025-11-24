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
     * Vyber temat, kde lze spravovat pracovniky kampani
     */
    public function selectTopic()
    {
        $user = auth()->user();

        // Pokud je uzivatel admin -muze spravovat vse
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            $topics = Topic::with('campaigns')->get();
        }

        // Pro nespravce - vidi jen kampane, ktere muze spravovat
        else {
            $topics = Topic::with(['campaigns' => function ($q) use ($user) {
                    // nacte jen kampane, ktere patri aktualnimu uzivateli
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
     * Hlavni prehled pracovniku kampane
     */
    public function manageWorkers(Campaign $campaign)
{
    $this->authorize('manageWorkers', [Campaign::class, $campaign]);

    // Nacteni vsech dulezitych vztahu (optimalizace)
    $campaign->load([
        'manager',
        'steps.user',                 // koordinatori kroku
        'steps.activities.users',     // pracovnici aktivit
        'workers',                    // pracovnici kampane (pivot)
    ]);

    // ID pracovniku z pivotu campaign_user
    $workerIds = $campaign->workers->pluck('id');

    // ID spravce kampane
    $managerId = $campaign->user_id;

    // ID koordinatoru z jednotlivych kroku
    $coordinatorIds = $campaign->steps
        ->pluck('user_id')
        ->filter();

    // ID uzivatelu z aktivit
    $activityUserIds = $campaign->steps
        ->flatMap(function ($step) {
            return $step->activities->flatMap(function ($activity) {
                return $activity->users->pluck('id');
            });
        });

    // Slouceni vsech ID zapojenych uzivatelu
    $allAssignedIds = collect()
        ->merge($workerIds)
        ->when($managerId, fn($c) => $c->push($managerId))
        ->merge($coordinatorIds)
        ->merge($activityUserIds)
        ->unique()
        ->values();

    // Seznam skutecnych pracovniku v kampani
    $assignedUsers = User::whereIn('id', $allAssignedIds)
        ->orderBy('surname')
        ->orderBy('name')
        ->get();

    // urceni pozice v kampani
    $assignedUsers->transform(function ($user) use ($campaign) {

        //  defaultni pozice
        $position = 'activity_worker';

        // Spravce kampane
        if ($campaign->user_id === $user->id) {
            $position = 'manager';
        }

        // Koordinator kroku
        $isCoordinator = $campaign->steps()
            ->where('user_id', $user->id)
            ->exists();

        if ($isCoordinator && $position !== 'manager') {
            $position = 'coordinator';
        }

        // Pracovnik kampane 
        $isWorker = $campaign->workers()
            ->where('users.id', $user->id)
            ->exists();

        if ($isWorker && !in_array($position, ['manager', 'coordinator'])) {
            $position = 'worker';
        }

        // Pro FE
        $user->campaign_position = $position;

        return $user;
    });

    // Uzivatele, ktere nelze znovu pridat
    $excluded = $allAssignedIds->all();

    // Dostupni uzivatele pro pridani
    $availableUsers = User::whereNotIn('id', $excluded)
        ->orderBy('surname')
        ->orderBy('name')
        ->get();

    // Vsechny uzivatele pro dropdown spravce
    $allUsers = User::orderBy('surname')->orderBy('name')->get();

    // Koordinatori nesmi byt spravce kampane
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
     * Pridani pracovnika do kampane
     */
    public function addWorker(Request $request, Campaign $campaign)
    {
        $user = User::findOrFail($request->user_id);

        // Prida do pivot tabulky bez odstraneni existujicich
        $campaign->workers()->syncWithoutDetaching([$user->id]);

        // Prepocte roli, aby mel alespon worker
        $user->refreshRole();

        return back()->with('success', 'Pracovnik pridan.');
    }

    /**
     * Odebrani pracovnika + kontrola roli
     */
    public function removeWorker(Campaign $campaign, User $user)
{
    $this->authorize('manageWorkers', [Campaign::class, $campaign]);

    // Kontrola: ma uzivatel zpravy?
    $hasMessages = $campaign->steps
        ->flatMap->activities
        ->flatMap->messages
        ->where('user_id', $user->id)
        ->isNotEmpty();

    if ($hasMessages) {
        return back()->with('error', 'Uzivatele nelze odebrat – ma podanou zpravu.');
    }

    // odebrani
    if ($campaign->user_id === $user->id) {
        $campaign->update(['user_id' => null]);
    }

    $campaign->steps()->where('user_id', $user->id)->update(['user_id' => null]);
    $campaign->workers()->detach($user->id);

    $user->refreshRole();

    return back()->with('success', 'Pracovnik byl odebran.');
}


    /**
     * Zmena koordinatora kroku
     */
    public function updateCoordinator(Request $request, Campaign $campaign, CampaignStep $step)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $new = $request->user_id;
        $old = $step->user_id;

        // Nastaveni noveho koordinatora
        $step->update(['user_id' => $new ?: null]);

        // Pokud existoval stary koordinator
        if ($old && $old != $new) {

            $oldUser = User::find($old);

            // Zjisti, zda stale koordinuje nejaky jiny krok
            $stillCoordinator = $campaign->steps()
                ->where('user_id', $old)
                ->exists();

            if (!$stillCoordinator) {
                $campaign->workers()->detach($old);
            }

            $oldUser->refreshRole();
        }

        // Novy koordinator se zaroven pridava mezi pracovniky
        if ($new) {
            $campaign->workers()->syncWithoutDetaching([$new]);

            $newUser = User::find($new);
            $newUser->refreshRole();
        }

        return back()->with('success', 'Koordinator kroku aktualizovan.');
    }

    /**
     * Zmena spravce kampane
     */
    public function updateManager(Request $request, Campaign $campaign)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $new = $request->user_id;
        $old = $campaign->user_id;

        // Nastavit noveho spravce kampane
        $campaign->update(['user_id' => $new]);

        // Pokud existoval puvodni spravce
        if ($old && $old != $new) {

            $oldUser = User::find($old);

            // Odebrat jej z koordinace kroku
            $campaign->steps()->where('user_id', $old)->update(['user_id' => null]);

            // Odebrat z pracovniku
            $campaign->workers()->detach($old);

            // Prepocti roli
            $oldUser->refreshRole();
        }

        // Pridat noveho spravce jako pracovnika
        if ($new) {
            $campaign->workers()->syncWithoutDetaching([$new]);

            $newUser = User::find($new);
            $newUser->refreshRole();
        }

        return back()->with('success', 'Spravce kampane aktualizovan.');
    }

}
