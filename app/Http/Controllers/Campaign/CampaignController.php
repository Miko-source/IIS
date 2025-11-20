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
    public function create(Topic $topic)
    {
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


    public function show(Topic $topic, Campaign $campaign)
    {
        $this->authorize('view', $campaign);
        
        // vybrat všechno kde role není admin
        $users = User::where('role', '!=', UserRole::ADMIN)->get();
        
        return view('campaigns.show', compact('topic', 'campaign', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
