<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintRegistry\Composite;

use ResponsibleTime\Activity\Decision\IsActivityRecordExceedingTimeLimit;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class ActivitySprintWithArtificialDurationOfPreviousActivityAddedToSprintRegistry
{
    /** @var SprintRegistryRecord */
    private $data;

    public function __construct(SprintRegistry $sprintRegistry, SprintRegistryRecord $activitySprintWithArtificialDurationOfPreviousActivity, IsActivityRecordExceedingTimeLimit $testOnActivityLength, ActivityRecordInterface $currentActivityRecord)
    {
        // End up the ongoing sprint as we detected activity record exceeding the limit, which means we detected inactivity
        $sprintRegistry->add($activitySprintWithArtificialDurationOfPreviousActivity); // @TODO: Make sure duplicates are not added if it is the last activity that lasts too long

        // Add in inactivity covering the following inactivity gap
        $activitySprintWithArtificialDurationOfPreviousActivity = new SprintRegistryRecord(
            $testOnActivityLength->getInactivityRecordWithDuration()->getActivityRecord(),
            $testOnActivityLength->getInactivityRecordWithDuration(),
            $currentActivityRecord
        );
        $sprintRegistry->add($activitySprintWithArtificialDurationOfPreviousActivity); // @TODO: Make sure duplicates are not added if it is the last activity that lasts too long

        // Add the current record with artificial duration
        $activitySprintWithArtificialDurationOfPreviousActivity = new SprintRegistryRecord(
            $testOnActivityLength->getActivityRecordWithDurationArtificialAfterInactivity()->getActivityRecord(),
            $testOnActivityLength->getActivityRecordWithDurationArtificialAfterInactivity(),
            null
        );

        $this->data = $activitySprintWithArtificialDurationOfPreviousActivity;
    }

    public function getData(): SprintRegistryRecord
    {
        return $this->data;
    }
}
