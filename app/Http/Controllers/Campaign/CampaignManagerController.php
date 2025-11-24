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
    // zmenit nebo pridat spravce kampane
    public function update(Request $request, Topic $topic, Campaign $campaign)
    {
        // kontrola opravneni pro spravu spravcu kampane
        $this->authorize('manageManager', Campaign::class);
        
        // validace vstupu
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        // novy spravce
        $newManager = User::findOrFail($validated['user_id']);

        // puvodni spravce (pokud existuje)
        $oldManager = User::find($campaign->user_id);

        // ulozeni noveho spravce do kampane
        $campaign->update(['user_id' => $validated['user_id']]);

        // aktualizace role stareho spravce (pokud se zmenil)
        if ($oldManager && $oldManager->id !== $newManager->id) {
            // upravi roli podle toho, zda stale nekde spravuje
            $oldManager->refreshRole();
        }
        
        // aktualizace role noveho spravce
        $newManager->refreshRole();

        // presmerovani zpet na detail kampane
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Spravce kampane byl prirazen.');
    }

    // odebrat spravce kampane
    public function destroy(Topic $topic, Campaign $campaign)
    {
        // kontrola opravneni
        $this->authorize('manageManager', Campaign::class);
        
        // najdi aktualniho spravce kampane
        $oldManager = User::find($campaign->user_id);
        
        // odebrani spravce z kampane
        $campaign->update(['user_id' => null]);
        
        // aktualizace role stareho spravce (uz nemusim byt spravce)
        if ($oldManager) {
            $oldManager->refreshRole();
        }
        
        // presmerovani s hlaskou
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Spravce kampane byl odebran.');
    }
}
