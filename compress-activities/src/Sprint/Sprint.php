<?php
declare(strict_types=1);

namespace ResponsibleTime\Sprint;

use ResponsibleTime\Activity\Decision\IsSameActivity;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Sprint\Reset\ActivityRecordAndSprintReset;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class Sprint
{
    /** @var SprintRegistryRecord */
    private $lastSprintRegistryRecordAdded;

    public function __construct(SprintRegistry $sprintRegistry, ActivityRecordInterface $activityRecord, ?SprintRegistryRecord $sprintRegistryRecordWithDuration)
    {
        // First record
        // First record is always incomplete as we need data on the second activity to determine how long the first activity took
        // Therefore the first record always gets "default max duration" (which should be adjusted in case duration is treated to be completed ?? !!!!!!! )
        // --- ACTIVITY DECISIONS ---
        $reset = new ActivityRecordAndSprintReset($activityRecord);

        if ($this->isVeryFirstActivityRecord($sprintRegistryRecordWithDuration)) { // First record
            $sprintRegistryRecordWithDuration = $reset->getSprintRegistryRecordWithDurationArtificial();
        } else {
            $sprintRegistryRecordWithDuration = new SprintRegistryRecord(
                $sprintRegistryRecordWithDuration->getActivityRecordThatStartedSprint(),
                $reset->getActivityRecordWithDurationArtificial(),
                null
            );
        }

        // --- ACTIVITY SPRINT DECISIONS ---
        $isSameActivity = (new IsSameActivity($sprintRegistryRecordWithDuration, $activityRecord))->isSameActivity();

        // In case it is the same type of activity, we add up the time so at the end we have duration: FROM first activity of the type (start) TO this activity start+max activity duration possible (end).
        if (true === $isSameActivity) {
            $sprintRegistryRecordWithDuration = new SprintRegistryRecord(
                $sprintRegistryRecordWithDuration->getActivityRecordThatStartedSprint(),
                $reset->getActivityRecordWithDurationArtificial(),
                null
            );
        }

        // In case this is the activity starting the new sprint
        if (false === $isSameActivity) {
            $sprintRegistryRecordWithDuration = new SprintRegistryRecord(
                $sprintRegistryRecordWithDuration->getActivityRecordThatStartedSprint(),
                $sprintRegistryRecordWithDuration->getActivityRecordThatCompletedSprintWithArtificialDateTime(),
                $activityRecord
            );

            // Register the completed as it is fully done now
            $sprintRegistry->add($sprintRegistryRecordWithDuration);

            // Reset activity sprint to the new one -- same as in
            $sprintRegistryRecordWithDuration = $reset->getSprintRegistryRecordWithDurationArtificial();
        }

        // End
        $this->lastSprintRegistryRecordAdded = $sprintRegistryRecordWithDuration;
    }

    public function getLastSprintRegistryRecordAdded(): SprintRegistryRecord
    {
        return $this->lastSprintRegistryRecordAdded;
    }

    private function isVeryFirstActivityRecord(?SprintRegistryRecord $activitySprintWithDuration): bool
    {
        return null === $activitySprintWithDuration;
    }
}
