<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignStep;
use Illuminate\Support\Collection;

class StepStateService
{
    public function calculateStepStates(Campaign $campaign): array
    {
        $steps = $campaign->steps;

        return $steps->mapWithKeys(fn ($step) => [
            $step->id => [
                'show_complete' => $this->shouldShowCompleteButton($step, $steps),
            ],
        ])->toArray();
    }

    private function shouldShowCompleteButton(CampaignStep $step, Collection $allSteps): bool
    {
        if ($step->is_completed) {
            return false;
        }

        return $this->allActivitiesEvaluated($step)
            && $this->allPreviousStepsCompleted($step, $allSteps)
            && $this->canMarkComplete($step);
    }

    private function allActivitiesEvaluated(CampaignStep $step): bool
    {
        if ($step->activities->isEmpty()) {
            return false;
        }

        return $step->activities->every(fn ($activity) => $activity->isEvaluated());
    }

    private function allPreviousStepsCompleted(CampaignStep $step, Collection $allSteps): bool
    {
        return $allSteps
            ->where('order', '<', $step->order)
            ->every(fn ($s) => $s->is_completed);
    }

    private function canMarkComplete(CampaignStep $step): bool
    {
        $user = auth()->user();

        return $user && $user->can('markComplete', $step);
    }
}
