<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintWithDuration\RecordWithDuration\Last;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\SprintRegistry\Composite\ActivitySprintOfEveningInactivityRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistry;

class LastRecordAddedToSprintRegistry
{
    public function __construct(SprintRegistry $sprintRegistry, ActivityRecordInterface $currentActivity)
    {
        (
        new ActivitySprintOfEveningInactivityRegistry(
            $sprintRegistry,
            $currentActivity
        )
        )->getData();
    }
}
