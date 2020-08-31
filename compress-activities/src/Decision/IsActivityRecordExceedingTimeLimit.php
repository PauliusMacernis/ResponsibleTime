<?php
declare(strict_types=1);

namespace Activity\Decision;

use Activity\ActivityRecordAndSprintReset\ActivityRecordAndSprintReset;
use Activity\ActivityRecordWithDuration\ActivityRecordOnPowerOffWithDuration;
use Activity\ActivityRecordWithDuration\ActivityRecordWithDuration;
use Activity\Settings;

class IsActivityRecordExceedingTimeLimit
{
    /** @var bool */
    private $isActivityRecordExceedingTimeLimit;

    /** @var ActivityRecordWithDuration */
    private $activityRecordWithDurationTrimmedToFitLimits;

    /** @var ?ActivityRecordWithDuration */
    private $inactivityRecordWithDuration;

    public function __construct(ActivityRecordWithDuration $activityRecordWithDuration)
    {
        if($activityRecordWithDuration->getActivityRecordDuration()->getDurationInSeconds() > Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS) {
            $this->isActivityRecordExceedingTimeLimit = true;

            $reset = new ActivityRecordAndSprintReset($activityRecordWithDuration->getActivityRecord());
            $this->activityRecordWithDurationTrimmedToFitLimits = $reset->getActivityRecordWithDurationArtificial();

            $this->inactivityRecordWithDuration = new ActivityRecordOnPowerOffWithDuration($activityRecordWithDuration->getActivityRecordDuration()->getTimeEnd(), $reset->getActivityRecordWithDurationArtificial()->getActivityRecordDuration()->getTimeEnd());
        } else {
            $this->isActivityRecordExceedingTimeLimit = false;
            $this->activityRecordWithDurationTrimmedToFitLimits = $activityRecordWithDuration;
            $this->inactivityRecordWithDuration = null;
        }
    }

    public function isActivityRecordExceedingTimeLimit(): bool
    {
        return $this->isActivityRecordExceedingTimeLimit;
    }

    public function getActivityRecordWithDurationTrimmedToFitLimits(): ActivityRecordWithDuration
    {
        return $this->activityRecordWithDurationTrimmedToFitLimits;
    }

    public function getInactivityRecordWithDuration(): ?ActivityRecordWithDuration
    {
        return $this->inactivityRecordWithDuration;
    }
}
