<?php
declare(strict_types=1);

namespace Activity\ActivityRecordWithDuration;

use Activity\ActivityRecord\ActivityRecordOnPowerOff;
use Activity\Duration;
use DateTimeInterface;

class ActivityRecordOnPowerOffWithDuration extends ActivityRecordWithDuration
{
    public function __construct(DateTimeInterface $dateTimeStart, DateTimeInterface $dateTimeEnd)
    {
        $this->activityRecord = new ActivityRecordOnPowerOff($dateTimeStart);
        $this->activityRecordDuration = new Duration(
            $dateTimeStart,
            $dateTimeEnd
        );
    }
}
