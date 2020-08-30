<?php
declare(strict_types=1);

namespace Activity\ActivityRecordAndSprintReset;

use Activity\ActivityRecord\ActivityRecord;
use Activity\ActivityRecordWithDuration\ActivityRecordWithDuration;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;
use Activity\Duration;
use Activity\Settings;
use DateInterval;

class ActivityRecordAndSprintReset
{
    /** @var ActivityRecordWithDuration */
    private $activityRecordWithDuration;

    /** @var ActivitySprintWithDuration */
    private $activitySprintWithDuration;

    public function __construct(ActivityRecord $activityRecord)
    {
        $activityRecordDateTimePlusMax = clone $activityRecord->getDateTime();
        $activityRecordDateTimePlusMax->add(new DateInterval(sprintf('PT%sS', Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS))); // @TODO: Should each type of activity record have a different "max activity record in seconds"? It is basically about how fast the mental stack releases the information after different type of application, e.g. terminal - 1 second, php storm - 5 minutes,
        $duration = new Duration(
            $activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
            $activityRecordDateTimePlusMax->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
        );

        $this->activityRecordWithDuration = new ActivityRecordWithDuration($activityRecord, $duration);
        $this->activitySprintWithDuration = new ActivitySprintWithDuration($activityRecord, $duration);
    }

    public function getActivityRecordWithDuration(): ActivityRecordWithDuration
    {
        return $this->activityRecordWithDuration;
    }

    public function getActivitySprintWithDuration(): ActivitySprintWithDuration
    {
        return $this->activitySprintWithDuration;
    }
}
