<?php
declare(strict_types=1);

namespace Activity\ActivityRecordAndSprintReset;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivityRecordWithDuration\ActivityRecordWithDuration;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDurationRecord;
use Activity\Duration;
use Activity\Settings;
use DateInterval;

class ActivityRecordAndSprintReset
{
    /** @var ActivityRecordWithDuration */
    private $activityRecordWithDurationArtificial;

    /** @var ActivitySprintWithDurationRecord */
    private $activitySprintWithDurationArtificial;

    public function __construct(ActivityRecordInterface $activityRecord)
    {
        $activityRecordDateTimePlusMax = clone $activityRecord->getDateTime();
        $activityRecordDateTimePlusMax->add(new DateInterval(sprintf('PT%sS', Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS))); // @TODO: Should each type of activity record have a different "max activity record in seconds"? It is basically about how fast the mental stack releases the information after different type of application, e.g. terminal - 1 second, php storm - 5 minutes,
        $durationArtificial = new Duration(
            $activityRecord->getDateTime(),
            $activityRecordDateTimePlusMax
        );

        $this->activityRecordWithDurationArtificial = new ActivityRecordWithDuration($activityRecord, $durationArtificial);

        $activityRecordWithDuration = new ActivityRecordWithDuration($activityRecord, $durationArtificial);
        $this->activitySprintWithDurationArtificial = new ActivitySprintWithDurationRecord($activityRecord, $activityRecordWithDuration, null);
    }

    public function getActivityRecordWithDurationArtificial(): ActivityRecordWithDuration
    {
        return $this->activityRecordWithDurationArtificial;
    }

    public function getActivitySprintWithDurationArtificial(): ActivitySprintWithDurationRecord
    {
        return $this->activitySprintWithDurationArtificial;
    }
}
