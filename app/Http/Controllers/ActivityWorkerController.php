<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityWorkerController extends Controller
{
    public function index(Activity $activity)
    {
        $this->authorize('manageWorkers', $activity);

        $activity->load(['step.campaign', 'users', 'messages']);
        $campaign = $activity->step->campaign;

        //  Přidělení realizátoři
        $assignedUsers = $activity->users()
            ->withPivot('is_confirmed', 'is_completed')
            ->get();

        //  Všichni potenciální realizátoři – bez ohledu na to,
        //    zda jsou v kampani
        $availableWorkers = User::whereIn('role', [
                'worker',
                'coordinator',
                'campaign_manager',
                'admin'
            ])
            ->whereDoesntHave('activities', function ($q) use ($activity) {
                $q->where('activities.id', $activity->id);
            })
            ->get();

        //  Uživatelé s podanou zprávou – nesmí se odebrat
        $usersWithMessages = $activity->messages()
            ->pluck('user_id')
            ->unique()
            ->toArray();

        return view('activities.workers', [
            'activity'          => $activity,
            'campaign'          => $campaign,
            'assignedUsers'     => $assignedUsers,
            'availableWorkers'  => $availableWorkers,
            'usersWithMessages' => $usersWithMessages,
        ]);
    }

    public function store(Request $request, Activity $activity)
    {
        $this->authorize('manageWorkers', $activity);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $userId = $data['user_id'];
        $campaign = $activity->step->campaign;

        //
        // Pokud není v kampani → automaticky přidat
        //
        $isInCampaign = $campaign->workers()
            ->where('users.id', $userId)
            ->exists();

        if (!$isInCampaign) {
            $campaign->workers()->attach($userId);
        }

        //
        // Pokud už je přiřazen → nelze znovu
        //
        $alreadyAssigned = $activity->users()
            ->where('users.id', $userId)
            ->exists();

        if ($alreadyAssigned) {
            return back()->with('error', 'Uživatel je již k aktivitě přiřazen.');
        }

        //
        //  Přidat k aktivitě jako potvrzeného realizátora
        //
        $activity->users()->attach($userId, [
            'is_confirmed' => 1,
            'is_completed' => 0,
        ]);

        return back()->with('success', 'Pracovník byl přidán k aktivitě a automaticky i ke kampani.');
    }

public function destroy(Activity $activity, User $user)
{
    $this->authorize('manageWorkers', $activity);

    //
    // SMAZAT zprávy odebraného uživatele
    //
    $activity->messages()
        ->where('user_id', $user->id)
        ->delete();

    //
    //  Odebrat vztah z activity_user
    //
    $activity->users()->detach($user->id);

    //
    //  Znovu načíst zbývající uživatele
    //
    $remainingUsers = $activity->users()->get();

    //
    //  Zjistit, zda KAŽDÝ zbývající uživatel má alespoň jednu zprávu
    //
    $allHaveMessages = $remainingUsers->every(function ($u) use ($activity) {
        return $activity->messages()
            ->where('user_id', $u->id)
            ->exists();
    });

    //

    //
    if ($allHaveMessages && $remainingUsers->count() > 0) {
        $activity->completed = true;
    } else {
        $activity->completed = false;
    }

    $activity->save();

    return back()->with('success', 'Pracovník byl odebrán z aktivity.');
}

}
