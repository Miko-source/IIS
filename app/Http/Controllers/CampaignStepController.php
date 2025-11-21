<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;    


use App\Models\CampaignStep;     
use App\Models\User; 

class CampaignStepController extends Controller
{
    public function index(Campaign $campaign)
    {
        $steps = $campaign->steps()->orderBy('order')->get();
        return view('campaigns.steps.index', compact('campaign', 'steps'));
    }

    public function create(Campaign $campaign)
    {
        $coordinators = User::where('role', 'coordinator')->get();
        return view('campaigns.steps.create', compact('campaign', 'coordinators'));
    }

public function store(Request $request, Campaign $campaign)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'order'       => 'required|integer|min:1',
        'user_id'     => 'required|exists:users,id',
        'description' => 'nullable|string',
    ]);

    CampaignStep::create([
        'campaign_id' => $campaign->id,
        'name'        => $request->name,
        'order'       => $request->order,
        'user_id'     => $request->user_id,
        'description' => $request->description,
    ]);

    return redirect()
        ->route('campaign.steps.index', $campaign->id)
        ->with('success', 'Krok kampaně byl vytvořen.');
}

public function show(Campaign $campaign, CampaignStep $step)
{
    $activities = $step->activities;

    // seznam možných koordinátorů
    // můžeš omezit podle rolí nebo použít všichni
    $coordinators = \App\Models\User::where('id', '!=', $campaign->user_id)->get();

    return view('campaigns.steps.show', [
        'campaign'     => $campaign,
        'step'         => $step,
        'activities'   => $activities,
        'coordinators' => $coordinators,
    ]);
}

public function edit(Campaign $campaign, CampaignStep $step)
{
    $coordinators = User::where('role', 'coordinator')->get();

    return view('campaigns.steps.edit', compact('campaign', 'step', 'coordinators'));
}

public function update(Request $request, Campaign $campaign, CampaignStep $step)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'order'       => 'required|integer|min:1',
        'user_id'     => 'required|exists:users,id',
        'description' => 'nullable|string',
    ]);

    $step->update($request->only(['name', 'order', 'user_id', 'description']));

    return redirect()
        ->route('campaign.steps.show', [$campaign->id, $step->id])
        ->with('success', 'Krok kampaně byl upraven.');
}



}

