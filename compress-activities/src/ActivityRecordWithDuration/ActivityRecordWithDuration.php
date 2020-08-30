<?php
declare(strict_types=1);

namespace Activity\ActivityRecordWithDuration;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\Duration;
use Activity\Settings;

class ActivityRecordWithDuration
{
    /** @var ActivityRecordInterface */
    private $activityRecord;
    private $activityRecordDuration;

    public function __construct(ActivityRecordInterface $activityRecord, Duration $duration)
    {
        $this->activityRecord = $activityRecord;
        $this->activityRecordDuration = new Duration(
            $activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
            $duration->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
        );
    }

    public function getActivityRecord(): ActivityRecordInterface
    {
        return $this->activityRecord;
    }

    public function getActivityRecordDuration(): Duration
    {
        return $this->activityRecordDuration;
    }
}
