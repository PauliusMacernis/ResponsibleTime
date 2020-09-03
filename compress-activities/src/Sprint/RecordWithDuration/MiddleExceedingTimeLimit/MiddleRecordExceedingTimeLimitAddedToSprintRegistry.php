<?php
declare(strict_types=1);

namespace ResponsibleTime\Sprint\RecordWithDuration\MiddleExceedingTimeLimit;

use ResponsibleTime\Activity\Decision\IsActivityRecordExceedingTimeLimit;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\SprintRegistry\Composite\ActivitySprintWithArtificialDurationOfPreviousActivityAddedToSprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class MiddleRecordExceedingTimeLimitAddedToSprintRegistry
{
    private $data;

    public function __construct(SprintRegistry $sprintRegistry, SprintRegistryRecord $sprintWithPreviousActivity, IsActivityRecordExceedingTimeLimit $testOnActivityLength, ActivityRecordInterface $currentActivity)
    {
        $this->data = (
        new ActivitySprintWithArtificialDurationOfPreviousActivityAddedToSprintRegistry(
            $sprintRegistry,
            $sprintWithPreviousActivity,
            $testOnActivityLength,
            $currentActivity
        )
        )->getData();
    }

    public function getData(): SprintRegistryRecord
    {
        return $this->data;
    }
}
