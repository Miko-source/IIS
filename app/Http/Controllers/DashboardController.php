<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ActivityUser;
use App\Models\Campaign;
use App\Models\CampaignStep;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Activity;



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

        // Aktivní
        $assigned = ActivityUser::with([
                'activity',
                'activity.step',
                'activity.step.campaign',
            ])
            ->where('user_id', $user->id)
            ->where('is_confirmed', true)
            ->where('is_completed', false)
            ->get();

        // Uzavřené aktivity
        $closedActivityIds = Message::where('user_id', $user->id)
            ->pluck('activity_id')
            ->unique();

        $closedActivities = \App\Models\Activity::with([
                'step',
                'step.campaign',
                'messages'  
            ])
            ->whereIn('id', $closedActivityIds)
            ->get();

        return view('workspace.index', compact('assigned', 'closedActivities'));
    }


    public function submitReport(Request $request, ActivityUser $activityUser)
    {
        $request->validate([
            'content' => 'required|string|min:5|max:5000',
            'success' => 'required|boolean',
        ]);

        // jen uzivatel, ktery je prihlasen
        if ($activityUser->user_id !== Auth::id()) {
            abort(403);
        }

        // ulozit zpravu
        Message::create([
            'activity_id' => $activityUser->activity_id,
            'user_id'     => Auth::id(),
            'content'     => $request->content,
            'success'     => $request->success,
        ]);

        // oznacit tohoto workera jako dokončeného
        $activityUser->update([
            'is_completed' => true,
        ]);

        // získat aktivitu
        $activity = $activityUser->activity;

        // přepočítat stav aktivity úplně stejně jako confirm_activity
        $activity->recalculateCompletion();



        return back()->with('status', 'Zpráva byla odeslána a aktivita byla uzavřena.');
    }

        public function campaigns()
        {
            $user = Auth::user();

            // společný dotaz pro ADMINA i ostatní:
            $query = Campaign::with([
                'topic',
                'steps.activities.users'  
            ]);

            // ADMIN vidí VŠE
            if (!$user->hasRoleOrHigher(UserRole::ADMIN)) {
                // běžný uživatel vidí jen své kampaně
                $query->where('user_id', $user->id);
            }

            $campaigns = $query
                ->orderBy('topic_id')
                ->orderBy('name')
                ->get();

            // seskupíme podle téma -> kvůli přehledu
            $campaignsByTopic = $campaigns->groupBy('topic_id');

            return view('dashboard.campaigns.index', compact('campaignsByTopic'));
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






}
