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

        // ziskani role prihlaseneho uzivatele
        $role = $user->role instanceof UserRole
            ? $user->role->value
            : $user->role;

        // dotaz na vsechny nepotvrzene zadosti
        $query = ActivityUser::with([
                'user',
                'activity',
                'activity.step',
                'activity.step.campaign',
            ])
            ->where('is_confirmed', false);

        // campaign_manager, vidi jen zadosti v kampanich ktere spravuje
        if ($role === 'campaign_manager') {
            $campaignIds = Campaign::where('user_id', $user->id)->pluck('id');

            $query->whereHas('activity.step.campaign', function ($q) use ($campaignIds) {
                $q->whereIn('id', $campaignIds);
            });

        } elseif ($role === 'coordinator') {
            // koordinator vidi zadosti jen u kroku, ktere ma prirazene
            $stepIds = CampaignStep::where('user_id', $user->id)->pluck('id');

            $query->whereHas('activity.step', function ($q) use ($stepIds) {
                $q->whereIn('id', $stepIds);
            });
        }

        // nacteni vysledku s paginaci
        $requests = $query->paginate(10);

        return view('dashboard', compact('requests'));
    }

    public function myRequests()
    {
        $user = auth()->user();

        // vsechny aktivity na ktere je uzivatel prihlasen
        $activities = $user->activities()
            ->with(['step.campaign'])
            ->get();

        return view('dashboard.my-requests', compact('activities'));
    }

    public function workspace()
    {
        $user = Auth::user();

        // aktivni aktivity uzivatele (prihlasen + potvrzen + nedokonceno)
        $assigned = ActivityUser::with([
                'activity',
                'activity.step',
                'activity.step.campaign',
            ])
            ->where('user_id', $user->id)
            ->where('is_confirmed', true)
            ->where('is_completed', false)
            ->get();

        // zjisteni aktivit, kde uzivatel odeslal zpravu (uzavrene aktivity)
        $closedActivityIds = Message::where('user_id', $user->id)
            ->pluck('activity_id')
            ->unique();

        // nacteni uzavrenych aktivit i s hlaskami
        $closedActivities = Activity::with([
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
        // validace vstupu zpravy
        $request->validate([
            'content' => 'required|string|min:5|max:5000',
            'success' => 'required|boolean',
        ]);

        // kontrola, ze report odesila worker prirazeny k aktivite
        if ($activityUser->user_id !== Auth::id()) {
            abort(403);
        }

        // vytvoreni zpravy o uspechu/neuspechu
        Message::create([
            'activity_id' => $activityUser->activity_id,
            'user_id'     => Auth::id(),
            'content'     => $request->content,
            'success'     => $request->success,
        ]);

        // oznaceni uzivatele jako dokonceneho
        $activityUser->update([
            'is_completed' => true,
        ]);

        // prepocteni stavu cele aktivity
        $activity = $activityUser->activity;
        $activity->recalculateCompletion();

        return back()->with('status', 'Zprava byla odeslana a aktivita byla uzavrena.');
    }

    public function campaigns()
    {
        $user = Auth::user();

        // zakladni dotaz vcetne vazeb
        $query = Campaign::with([
            'topic',
            'steps.activities.users',
        ]);

        // omezeni pro ne-adminy – vidi jen kampane, kde maji roli
        if (!$user->hasRoleOrHigher(UserRole::ADMIN)) {

            $query->where(function ($q) use ($user) {

                // kampane kde je spravce
                $q->where('user_id', $user->id)

                // kampane kde je uzivatel pridany jako member
                ->orWhereHas('users', function ($q) use ($user) {
                    $q->where('campaign_user.user_id', $user->id);
                })

                // kampane u kroku ktere koordinuje
                ->orWhereHas('steps', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            });
        }

        // trideni a nacteni
        $campaigns = $query
            ->orderBy('topic_id')
            ->orderBy('name')
            ->get();

        // seskupeni podle tematu
        $campaignsByTopic = $campaigns->groupBy('topic_id');

        return view('dashboard.campaigns.index', compact('campaignsByTopic'));
    }

    public function campaignDetail(Campaign $campaign)
    {
        $user = Auth::user();

        // pristup jen pro admina nebo spravce dane kampane
        if (!($user->hasRoleOrHigher(UserRole::ADMIN) || $campaign->user_id === $user->id)) {
            abort(403);
        }

        // nacteni podrobnych vazeb kampane
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

        // kontrola opravneni na mazani kroku
        if (!($user->hasRoleOrHigher(UserRole::ADMIN) || $campaign->user_id === $user->id)) {
            abort(403);
        }

        // krok lze smazat jen pokud je splnen
        if (!$step->isCompletedSuccessfully()) {
            return back()->with('error', 'Krok nemuze byt odstranen, protoze neni kompletne splnen.');
        }

        // smazani kroku
        $step->delete();

        return back()->with('status', 'Krok byl uspesne odstranen.');
    }
}
