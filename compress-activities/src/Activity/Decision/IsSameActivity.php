<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Decision;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

/**
 * Do activity belong to the same sprint or not. This class answers this question.
 */
class IsSameActivity
{
    /** @var bool */
    private $isSameActivity;

    public function __construct(SprintRegistryRecord $activitySprintWithDuration, ActivityRecordInterface $activityRecord)
    {
        $this->isSameActivity = ($activitySprintWithDuration->getActivityRecordThatStartedSprint()->getWindowTitle()->__toString() === $activityRecord->getWindowTitle()->__toString());
    }

    public function isSameActivity(): bool
    {
        return $this->isSameActivity;
    }
}
