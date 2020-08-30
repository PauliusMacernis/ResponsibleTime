<?php
declare(strict_types=1);

namespace Activity\Decision;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;

/**
 * Do activity belong to the same sprint or not. This class answers this question.
 */
class IsSameActivity
{
    /** @var bool */
    private $isSameActivity;

    public function __construct(ActivitySprintWithDuration $activitySprintWithDuration, ActivityRecordInterface $activityRecord)
    {
        $this->isSameActivity = ($activitySprintWithDuration->getActivityRecordThatStartedSprint()->getWindowTitle()->__toString() === $activityRecord->getWindowTitle()->__toString());
    }

    public function isSameActivity(): bool
    {
        return $this->isSameActivity;
    }
}
