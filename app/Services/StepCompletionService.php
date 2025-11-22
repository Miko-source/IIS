<?php

namespace App\Services;

use App\Models\CampaignStep;

class StepCompletionService
{
    public function canComplete(CampaignStep $step): array
    {
        if ($step->activities->isEmpty()) {
            return $this->error('Krok nemá žádné aktivity. Nelze jej označit jako splněný.');
        }

        $unevaluatedError = $this->findUnevaluatedActivity($step);
        if ($unevaluatedError) {
            return $this->error($unevaluatedError);
        }

        if ($this->hasUnfinishedPreviousSteps($step)) {
            return $this->error('Neplatné pořadí: nejprve dokončete předchozí kroky.');
        }

        return ['can_complete' => true];
    }

    public function complete(CampaignStep $step): void
    {
        $step->update(['is_completed' => true]);
    }

    private function findUnevaluatedActivity(CampaignStep $step): ?string
    {
        foreach ($step->activities as $activity) {
            if ($activity->isEvaluated()) {
                continue;
            }

            // no confirmed worker
            if (! $activity->hasConfirmedWorkers()) {
                return "Aktivita '{$activity->name}' nemá žádné potvrzené pracovníky. Musí být přiřazen alespoň jeden pracovník.";
            }
        }

        return null;
    }

    private function hasUnfinishedPreviousSteps(CampaignStep $step): bool
    {
        return $step->campaign
            ->steps()
            ->where('order', '<', $step->order)
            ->where('is_completed', '!=', true)
            ->exists();
    }

    private function error(string $message): array
    {
        return [
            'can_complete' => false,
            'error' => $message,
        ];
    }
}
