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
use App\Services\StepStateService;
use Illuminate\Support\Facades\Log;


class CampaignController extends Controller
{
    public function __construct(
        private StepStateService $stepStateService
    ) {}

    //creates new campaign 
    public function create(Topic $topic )
    {
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

        $campaign = $topic->campaigns()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign])
            ->with('success', 'Kampaň byla vytvořena.');
    }

    public function show(Topic $topic, Campaign $campaign)
    {
        //check policys
        $this->authorize('view', $campaign);
        $users = User::where('role', '!=', UserRole::ADMIN)
            ->select('id', 'name', 'surname', 'email')
            ->get();

        // přednačíst kroky a potřebné relace
        $campaign->load([
            'steps' => function ($query) {
                $query->with([
                    'user:id,name,surname',
                    'activities.latestMessage',
                ])->orderBy('order');
            },
        ]);

        return view('campaigns.show', [
            'topic' => $topic,
            'campaign' => $campaign,
            'users' => $users,
            'stepStates' => $this->stepStateService->calculateStepStates($campaign),
        ]);
    }

    public function edit(Topic $topic, Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign, 'edit_campaign' => 1]);
    }

    public function update(Request $request, Topic $topic, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validateWithBag('campaignEdit', [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // DEBUG
        \Log::info('Campaign update before', [
            'campaign_id' => $campaign->id,
            'start_date_old' => $campaign->start_date,
            'start_date_new' => $validated['start_date'] ?? null,
        ]);

        $campaign->update($validated);

        // DEBUG
        $campaign->refresh();
        \Log::info('Campaign update after', [
            'campaign_id' => $campaign->id,
            'start_date_db' => $campaign->start_date,
        ]);

        return redirect()
            ->route('topics.campaigns.show', [$topic, $campaign, 'edit_campaign' => 1])
            ->with('success', 'Kampaň byla upravena.');
    }


    public function destroy(Topic $topic, Campaign $campaign)
    {
        $this->authorize('delete', $campaign);

        $campaign->delete();

        return redirect()
            ->route('topics.show', $topic)
            ->with('success', 'Kampaň byla smazána.');
    }

}
