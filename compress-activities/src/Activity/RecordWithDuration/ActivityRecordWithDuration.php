<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\RecordWithDuration;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Duration\Duration;

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
