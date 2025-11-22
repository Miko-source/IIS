<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ActivityUser;
use App\Models\Campaign;
use App\Models\CampaignStep;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\Message;



class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // role 
        $role = $user->role instanceof UserRole
            ? $user->role->value
            : $user->role;

        // nepotvrzene zadosti
        $query = ActivityUser::with([
                'user',
                'activity',
                'activity.step',
                'activity.step.campaign',
            ])
            ->where('is_confirmed', false);

        // ADMIN vidi vse
        if ($role === 'campaign_manager') {
            // spravce kampane jen kampane ktere spravuju
            $campaignIds = Campaign::where('user_id', $user->id)->pluck('id');

            $query->whereHas('activity.step.campaign', function ($q) use ($campaignIds) {
                $q->whereIn('id', $campaignIds);
            });

        } elseif ($role === 'coordinator') {
            // koordinator pouze pridelene kroky
            $stepIds = CampaignStep::where('user_id', $user->id)->pluck('id');

            $query->whereHas('activity.step', function ($q) use ($stepIds) {
                $q->whereIn('id', $stepIds);
            });
        }

        
        $requests = $query->paginate(10);

        return view('dashboard', compact('requests'));
    }

    public function myRequests()
    {
        $user = auth()->user();

        // všechny aktivity na ktere je prihlasen
        $activities = $user->activities()
            ->with(['step.campaign'])
            ->get();

        return view('dashboard.my-requests', compact('activities'));
    }

    public function workspace()
{
    $user = Auth::user();

    // Uzavřené aktivity (tj. ty, na které uživatel poslal zprávu)
    $closedActivityIds = Message::where('user_id', $user->id)
        ->pluck('activity_id')
        ->unique();

    // Aktivní aktivity (pouze potvrzené a bez zprávy)
    $assigned = ActivityUser::with([
            'activity',
            'activity.step',
            'activity.step.campaign',
        ])
        ->where('user_id', $user->id)
        ->where('is_confirmed', true)
        ->whereNotIn('activity_id', $closedActivityIds)
        ->get();

    // Uzavřené aktivity s detaily
    $closedActivities = \App\Models\Activity::with([
            'step',
            'step.campaign',
            'messages'
        ])
        ->whereIn('id', $closedActivityIds)
        ->get();

    return view('workspace.index', compact('assigned', 'closedActivities'));
}


public function submitReport(Request $request, \App\Models\Activity $activity)
{
    $user = Auth::user();

    // Validace
    $request->validate([
        'content' => 'required|string|min:5|max:5000',
        'success' => 'required|boolean',
    ]);

    // Musí být přihlášený a potvrzený
    $isParticipant = $activity->users()
        ->where('users.id', $user->id)
        ->wherePivot('is_confirmed', true)
        ->exists();

    if (! $isParticipant) {
        abort(403, 'K této aktivitě nemůžete posílat zprávu.');
    }

    // Uložit nebo aktualizovat zprávu
    Message::updateOrCreate(
        [
            'activity_id' => $activity->id,
            'user_id'     => $user->id,
        ],
        [
            'content' => $request->content,
            'success' => $request->success,
        ]
    );

    // přepočet stavu aktivity
    $this->recalculateActivityCompletion($activity);

    return back()->with('status', 'Tvoje zpráva byla uložena.');
}

private function recalculateActivityCompletion(\App\Models\Activity $activity): void
{
    // potvrzení uživatelé
    $confirmedUsers = $activity->users()
        ->wherePivot('is_confirmed', true)
        ->pluck('users.id')
        ->toArray();

    if (count($confirmedUsers) === 0) {
        $activity->update(['is_completed' => false]);
        return;
    }

    // kolik z nás poslalo zprávu
    $usersWithMessage = Message::where('activity_id', $activity->id)
        ->whereIn('user_id', $confirmedUsers)
        ->distinct()
        ->count('user_id');

    $activity->update([
        'is_completed' => ($usersWithMessage === count($confirmedUsers))
    ]);
}



    public function campaignDetail(Campaign $campaign)
    {
        $user = Auth::user();

        // admin nebo správce kampaně
        if (!($user->hasRoleOrHigher(UserRole::ADMIN) || $campaign->user_id === $user->id)) {
            abort(403);
        }

        $campaign->load([
            'topic',
            'steps.activities.messages.user',
        ]);

        return view('dashboard.campaigns.show', compact('campaign'));
    }

    public function deleteStep(CampaignStep $step)
    {
        $user = Auth::user();
        $campaign = $step->campaign;

        // správce kampaně nebo admin
        if (!($user->hasRoleOrHigher(UserRole::ADMIN) || $campaign->user_id === $user->id)) {
            abort(403);
        }

        // krok lze smazat jen pokud je splněn
        if (!$step->isCompletedSuccessfully()) {
            return back()->with('error', 'Krok nemůže být odstraněn, protože není kompletně splněný.');
        }

        // smazat krok
        $step->delete();

        return back()->with('status', 'Krok byl úspěšně odstraněn.');
    }

    public function campaigns()
{
    $user = Auth::user();

    // dotaz pro zobrazení kampaní
    $query = Campaign::with([
        'topic',
        'steps.activities.users',
    ]);

    // ADMIN → vidí vše
    if (!$user->hasRoleOrHigher(UserRole::ADMIN)) {
        // Správce kampaně → vidí jen kampaně, které vlastní
        $query->where('user_id', $user->id);
    }

    $campaigns = $query
        ->orderBy('topic_id')
        ->orderBy('name')
        ->get();

    // seskupíme kampaně podle témat pro přehledné zobrazení
    $campaignsByTopic = $campaigns->groupBy('topic_id');

    return view('dashboard.campaigns.index', compact('campaignsByTopic'));
}





}
