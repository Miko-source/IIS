<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;

class CampaignWorkerController extends Controller
{
    public function selectTopic()
    {
        $topics = Topic::with('campaigns')->get();

        return view('campaigns.manage', compact('topics'));
    }

    public function manageWorkers(Campaign $campaign)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $assignedUsers = $campaign->workers()->get();
        $availableUsers = User::whereIn('role', ['worker', 'coordinator'])->get();

        return view('campaigns.workers', compact('campaign', 'assignedUsers', 'availableUsers'));
    }

    public function addWorker(Request $request, Campaign $campaign)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $campaign->workers()->syncWithoutDetaching($request->user_id);

        return back()->with('success', 'Pracovník byl úspěšně přidán.');
    }

    public function removeWorker(Campaign $campaign, User $user)
    {
        $this->authorize('manageWorkers', [Campaign::class, $campaign]);

        $campaign->workers()->detach($user->id);

        return back()->with('success', 'Pracovník odstraněn.');
    }
}
