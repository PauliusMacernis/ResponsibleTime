<?php
declare(strict_types=1);

namespace ResponsibleTime\Sprint\RecordWithDuration\MiddleNotExceedingTimeLimit;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Sprint\Sprint;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class MiddleRecordNotExceedingTimeLimitAddedToSprintRegistry
{
    private $data;

    public function __construct(SprintRegistry $sprintRegistry, ActivityRecordInterface $currentActivity, SprintRegistryRecord $sprintWithPreviousActivity)
    {
        $this->data = (
        new Sprint(
            $sprintRegistry,
            $currentActivity,
            $sprintWithPreviousActivity
        )
        )->getLastSprintRegistryRecordAdded();
    }

    public function getData(): SprintRegistryRecord
    {
        return $this->data;
    }
}
