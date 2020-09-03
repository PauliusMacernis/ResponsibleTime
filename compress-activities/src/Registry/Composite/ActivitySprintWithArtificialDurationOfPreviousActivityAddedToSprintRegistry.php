<?php
declare(strict_types=1);

namespace Activity\Registry\Composite;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDurationRecord;
use Activity\Decision\IsActivityRecordExceedingTimeLimit;
use Activity\Registry\ActivitySprintWithDurationRegistry;

class ActivitySprintWithArtificialDurationOfPreviousActivityAddedToSprintRegistry
{
    /** @var ActivitySprintWithDurationRecord */
    private $data;

    public function __construct(ActivitySprintWithDurationRegistry $sprintRegistry, ActivitySprintWithDurationRecord $activitySprintWithArtificialDurationOfPreviousActivity, IsActivityRecordExceedingTimeLimit $testOnActivityLength, ActivityRecordInterface $currentActivityRecord)
    {
        // End up the ongoing sprint as we detected activity record exceeding the limit, which means we detected inactivity
        $sprintRegistry->add($activitySprintWithArtificialDurationOfPreviousActivity); // @TODO: Make sure duplicates are not added if it is the last activity that lasts too long

        // Add in inactivity covering the following inactivity gap
        $activitySprintWithArtificialDurationOfPreviousActivity = new ActivitySprintWithDurationRecord(
            $testOnActivityLength->getInactivityRecordWithDuration()->getActivityRecord(),
            $testOnActivityLength->getInactivityRecordWithDuration(),
            $currentActivityRecord
        );
        $sprintRegistry->add($activitySprintWithArtificialDurationOfPreviousActivity); // @TODO: Make sure duplicates are not added if it is the last activity that lasts too long

        // Add the current record with artificial duration
        $activitySprintWithArtificialDurationOfPreviousActivity = new ActivitySprintWithDurationRecord(
            $testOnActivityLength->getActivityRecordWithDurationArtificialAfterInactivity()->getActivityRecord(),
            $testOnActivityLength->getActivityRecordWithDurationArtificialAfterInactivity(),
            null
        );

        $this->data = $activitySprintWithArtificialDurationOfPreviousActivity;
    }

    public function getData(): ActivitySprintWithDurationRecord
    {
        return $this->data;
    }
}
