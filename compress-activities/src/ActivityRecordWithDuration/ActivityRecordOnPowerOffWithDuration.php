<?php
declare(strict_types=1);

namespace Activity\ActivityRecordWithDuration;

use Activity\ActivityRecord\ActivityRecordOnPowerOff;
use Activity\Duration;
use Activity\Settings;
use DateTimeInterface;

class ActivityRecordOnPowerOffWithDuration extends ActivityRecordWithDuration
{
    public function __construct(DateTimeInterface $dateTimeStart, DateTimeInterface $dateTimeEnd)
    {
        $this->activityRecord = new ActivityRecordOnPowerOff($dateTimeStart);
        $this->activityRecordDuration = new Duration(
            $dateTimeStart->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
            $dateTimeEnd->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
        );
    }
}
