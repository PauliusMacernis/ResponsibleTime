<?php
declare(strict_types=1);

namespace Activity;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivityRecordAndSprintReset\ActivityRecordAndSprintReset;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;
use Activity\ActivitySprintWithDurationRegistry\ActivitySprintWithDurationRegistry;
use Activity\Decision\IsSameActivity;

class ActivityRecordConversionToActivitySprint
{
    /** @var ActivitySprintWithDuration */
    private $activitySprintWithDuration;

    public function __construct(ActivitySprintWithDurationRegistry $sprintRegistry, ActivityRecordInterface $activityRecord, ?ActivitySprintWithDuration $activitySprintWithDuration)
    {
        // First record
        // First record is always incomplete as we need data on the second activity to determine how long the first activity took
        // Therefore the first record always gets "default max duration" (which should be adjusted in case duration is treated to be completed ?? !!!!!!! )
        // --- ACTIVITY DECISIONS ---
        $reset = new ActivityRecordAndSprintReset($activityRecord);

        if ($this->isVeryFirstActivityRecord($activitySprintWithDuration)) { // First record
            $activitySprintWithDuration = $reset->getActivitySprintWithDurationArtificial();
        } else {
            $activitySprintWithDuration = new ActivitySprintWithDuration(
                $activitySprintWithDuration->getActivityRecordThatStartedSprint(),
                $reset->getActivityRecordWithDurationArtificial(),
                null
            );
        }

        // --- ACTIVITY SPRINT DECISIONS ---
        $isSameActivity = (new IsSameActivity($activitySprintWithDuration, $activityRecord))->isSameActivity();

        // In case it is the same type of activity, we add up the time so at the end we have duration: FROM first activity of the type (start) TO this activity start+max activity duration possible (end).
        if (true === $isSameActivity) {
            $activitySprintWithDuration = new ActivitySprintWithDuration(
                $activitySprintWithDuration->getActivityRecordThatStartedSprint(),
                $reset->getActivityRecordWithDurationArtificial(),
                null
            );
        }

        // In case this is the activity starting the new sprint
        if (false === $isSameActivity) {
            $activitySprintWithDuration = new ActivitySprintWithDuration(
                $activitySprintWithDuration->getActivityRecordThatStartedSprint(),
                $activitySprintWithDuration->getActivityRecordThatCompletedSprintWithArtificialDateTime(),
                $activityRecord
            );

            // Register the completed as it is fully done now
            $sprintRegistry->add($activitySprintWithDuration);

            // Reset activity sprint to the new one -- same as in
            $activitySprintWithDuration = $reset->getActivitySprintWithDurationArtificial();
        }

        // End
        $this->activitySprintWithDuration = $activitySprintWithDuration;
    }

    public function getActivitySprintWithDuration(): ActivitySprintWithDuration
    {
        return $this->activitySprintWithDuration;
    }

    private function isVeryFirstActivityRecord(?ActivitySprintWithDuration $activitySprintWithDuration): bool
    {
        return null === $activitySprintWithDuration;
    }
}
