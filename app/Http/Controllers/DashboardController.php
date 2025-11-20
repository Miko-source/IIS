<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ActivityUser;
use App\Models\Campaign;
use App\Models\CampaignStep;
use App\Enums\UserRole;

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

        // všechny aktivity, na které se tento uživatel přihlásil
        $activities = $user->activities()
            ->with(['step.campaign'])
            ->get();

        return view('dashboard.my-requests', compact('activities'));
    }

}
