<?php
declare(strict_types=1);

namespace Activity\Registry\Composite;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;
use Activity\Registry\ActivitySprintWithDurationRegistry;
use Activity\Settings;
use DateTime;
use DateTimeZone;

/**
 * Takes care of the very first inactivity which happens from 00:00 up to the time the first record is found.
 */
class ActivitySprintOfEveningInactivityRegistry
{
    /** @var ActivitySprintWithDuration */
    private $data;

    public function __construct(ActivitySprintWithDurationRegistry $sprintRegistry, ActivityRecordInterface $currentActivityRecord)
    {
        // @TODO: Making 23:59:59 the latest because some systems may misunderstand the day change.
        // If misunderstanding will not be the case when connecting to other systems
        // then we may come back and change this to match the day entirely up to 24:00:00 of the same day, or 00:00:00 of the next day.
        $latestMidnight = DateTime::createFromFormat('Y-m-d H:i:s', $currentActivityRecord->getDateTime()->format('Y-m-d 23:59:59'), new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP));

        throw new \Exception(sprintf('TBD: Activity timeline up to the sprint end/max date, which is %s, maybe..', $latestMidnight->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)));

    }

    public function getData(): ActivitySprintWithDuration
    {
        return $this->data;
    }
}
