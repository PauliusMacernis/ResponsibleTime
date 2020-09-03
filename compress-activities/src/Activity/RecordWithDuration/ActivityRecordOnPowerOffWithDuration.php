<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\RecordWithDuration;

use DateTimeInterface;
use ResponsibleTime\Activity\Record\ActivityRecordOnPowerOff;
use ResponsibleTime\Duration\Duration;

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
