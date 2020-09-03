<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Decision;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Activity\RecordWithDuration\ActivityRecordOnPowerOffWithDuration;
use ResponsibleTime\Activity\RecordWithDuration\ActivityRecordWithDuration;
use ResponsibleTime\Duration\Duration;
use ResponsibleTime\Settings;
use ResponsibleTime\Sprint\Reset\ActivityRecordAndSprintReset;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class IsActivityRecordExceedingTimeLimit
{
    /** @var bool */
    private $isActivityRecordExceedingTimeLimit;

    /** @var ActivityRecordWithDuration */
    private $activityRecordWithDurationTrimmedToFitLimitsBeforeInactivity;

    /** @var ?ActivityRecordWithDuration */
    private $inactivityRecordWithDuration;

    /** @var ActivityRecordWithDuration */
    private $activityRecordWithDurationArtificialAfterInactivity;

    public function __construct(SprintRegistryRecord $activitySprintWithDurationWithoutCurrentActivityRecord, ActivityRecordInterface $activityRecordCurrent)
    {
        $previousActivityRecordWithDuration = new ActivityRecordWithDuration($activitySprintWithDurationWithoutCurrentActivityRecord->getLastActivityRecordWithDurationUpToDate()->getActivityRecord(),
            new Duration(
                $activitySprintWithDurationWithoutCurrentActivityRecord->getLastActivityRecordWithDurationUpToDate()->getActivityRecord()->getDateTime(),
                $activityRecordCurrent->getDateTime()
            )
        );

        if ($previousActivityRecordWithDuration->getActivityRecordDuration()->getDurationInSeconds() > Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS) {
            $this->isActivityRecordExceedingTimeLimit = true;

            $reset = new ActivityRecordAndSprintReset($previousActivityRecordWithDuration->getActivityRecord());
            $this->activityRecordWithDurationTrimmedToFitLimitsBeforeInactivity = $reset->getActivityRecordWithDurationArtificial();

            $inactivityRecordWithDuration = new ActivityRecordOnPowerOffWithDuration($reset->getActivityRecordWithDurationArtificial()->getActivityRecordDuration()->getDateTimeEnd(), $previousActivityRecordWithDuration->getActivityRecordDuration()->getDateTimeEnd());
            $this->inactivityRecordWithDuration = $inactivityRecordWithDuration;

            $reset = new ActivityRecordAndSprintReset($activityRecordCurrent);
            $this->activityRecordWithDurationArtificialAfterInactivity = $reset->getActivityRecordWithDurationArtificial();
        } else {
            $this->isActivityRecordExceedingTimeLimit = false;
            $this->activityRecordWithDurationTrimmedToFitLimitsBeforeInactivity = $previousActivityRecordWithDuration;
            $this->inactivityRecordWithDuration = null;
            $this->activityRecordWithDurationArtificialAfterInactivity = null;
        }
    }

    public function isActivityRecordExceedingTimeLimit(): bool
    {
        return $this->isActivityRecordExceedingTimeLimit;
    }

    public function getActivityRecordWithDurationTrimmedToFitLimitsBeforeInactivity(): ActivityRecordWithDuration
    {
        return $this->activityRecordWithDurationTrimmedToFitLimitsBeforeInactivity;
    }

    public function getInactivityRecordWithDuration(): ?ActivityRecordWithDuration
    {
        return $this->inactivityRecordWithDuration;
    }

    public function getActivityRecordWithDurationArtificialAfterInactivity(): ActivityRecordWithDuration
    {
        return $this->activityRecordWithDurationArtificialAfterInactivity;
    }
}
