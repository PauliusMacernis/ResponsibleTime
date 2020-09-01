<?php
declare(strict_types=1);

namespace Activity\ActivityRecordWithDuration;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\Duration;

class ActivityRecordWithDuration
{
    /** @var ActivityRecordInterface */
    protected $activityRecord;
    /** @var Duration */
    protected $activityRecordDuration;

    public function __construct(ActivityRecordInterface $activityRecord, Duration $duration)
    {
        $this->activityRecord = $activityRecord;
        $this->activityRecordDuration = new Duration(
            $activityRecord->getDateTime(),
            $duration->getDateTimeEnd()
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
