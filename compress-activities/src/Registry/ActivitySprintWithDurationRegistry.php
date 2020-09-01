<?php
declare(strict_types=1);

namespace Activity\Registry;

use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;

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
            . $activitySprintWithDuration->getActivitySprintDuration()->getDateTimeStartFormatted()
            . ' - '
            . $activitySprintWithDuration->getActivitySprintDuration()->getDateTimeEndFormatted()
            . '] '
            . "\n"
            . $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getWmClass()->__toString()
            . "\n"
            . $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getWindowTitle()->__toString()
            . "\n\n";
    }
}
