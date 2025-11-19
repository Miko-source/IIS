<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */
namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Topic;
use Illuminate\Http\Request;

class CampaignManagerController extends Controller
{
    // změnit/přidat správce
    public function update(Request $request, Topic $topic, Campaign $campaign)
    {
        $this->authorize('manageManager', Campaign::class);
        
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);
        
        $campaign->update(['user_id' => $validated['user_id']]);
        
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Správce kampaně byl přiřazen.');
    }

    // Odebrat správce
    public function destroy(Topic $topic, Campaign $campaign)
    {
        $this->authorize('manageManager', Campaign::class);
        
        $campaign->update(['user_id' => null]);
        
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Správce kampaně byl odebrán.');
    }
}
