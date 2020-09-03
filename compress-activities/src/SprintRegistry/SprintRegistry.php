<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintRegistry;

/**
 * Whatever we do with the found sprints...
 * Register to a separate file, output to stdout, etc.
 */
class SprintRegistry
{
    public function add(SprintRegistryRecord $activitySprintWithDuration): void
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
