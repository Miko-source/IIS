<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */
namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Campaign;
use App\Models\User;
use App\Enums\UserRole; 


class CampaignController extends Controller
{


    //tvori novou kampan na TopicsView
    public function create(Topic $topic )
    {
        //kontrola prav na kampane
        $this->authorize('create', Campaign::class);
        return view('campaigns.create', compact('topic'));
    }

    public function store(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $validated['topic_id'] = $topic->id;
        $validated['user_id'] = $request->user()->id;

        $campaign = Campaign::create($validated);

        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Kampaň byla vytvořena.');
    }
//tvori novou kampan na TopicsView

    public function show(Topic $topic, Campaign $campaign)
    {
        //kontrola prav na konretni isntanci kampane
        $this->authorize('view', $campaign);
        
        // vybrat všechno kde role není admin
        $users = User::where('role', '!=', UserRole::ADMIN)->get();
        
        return view('campaigns.show', compact('topic', 'campaign', 'users'));
    }

    public function edit(Topic $topic, Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign, 'edit_campaign' => 1]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
        $campaign->update($validated);
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Kampaň byla upravena.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic, Campaign $campaign)
    {
        $this->authorize('delete', $campaign);

        $campaign->delete();

        return redirect()
            ->route('topics.show', $topic)
            ->with('success', 'Kampaň byla smazána.');
    }

}
