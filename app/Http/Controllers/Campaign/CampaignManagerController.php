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
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRole;

class CampaignManagerController extends Controller
{
    // změnit/přidat správce
    public function update(Request $request, Topic $topic, Campaign $campaign)
    {
        $this->authorize('manageManager', Campaign::class);
        
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $newManager = User::findOrFail($validated['user_id']);
        $oldManager = User::find($campaign->user_id);

        $campaign->update(['user_id' => $validated['user_id']]);

        // Aktualizuj role (automaticky nastaví správnou roli)
        if ($oldManager && $oldManager->id !== $newManager->id) {
            $oldManager->refreshRole();
        }
        
        $newManager->refreshRole();

        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Správce kampaně byl přiřazen.');
    }

    // Odebrat správce
    public function destroy(Topic $topic, Campaign $campaign)
    {
        $this->authorize('manageManager', Campaign::class);
        
        $oldManager = User::find($campaign->user_id);
        
        $campaign->update(['user_id' => null]);
        
        if ($oldManager) {
            $oldManager->refreshRole();
        }
        
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Správce kampaně byl odebrán.');
    }
}