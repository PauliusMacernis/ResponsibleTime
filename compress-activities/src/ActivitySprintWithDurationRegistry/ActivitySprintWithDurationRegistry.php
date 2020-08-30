<?php
declare(strict_types=1);

namespace Activity\ActivitySprintWithDurationRegistry;

use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;
use Activity\Settings;

/**
 * Whatever we do with the found sprints...
 * Register to a separate file, output to stdout, etc.
 */
class ActivitySprintWithDurationRegistry
{
    public function add(ActivitySprintWithDuration $activitySprintWithDuration): void
    {
        echo
            "\n"
            . '['
            . $activitySprintWithDuration->getActivitySprintDuration()->getTimeStart()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            . ' - '
            . $activitySprintWithDuration->getActivitySprintDuration()->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            . '] '
            . "\n"
            . $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getWmClass()->__toString()
            . "\n"
            . $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getWindowTitle()->__toString()
            . "\n\n";
    }
}
