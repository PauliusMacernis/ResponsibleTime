<?php
declare(strict_types=1);

namespace Activity\Registry\Composite;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivityRecord\ActivityRecordOnPowerOff;
use Activity\ActivityRecordAndSprintReset\ActivityRecordAndSprintReset;
use Activity\ActivityRecordWithDuration\ActivityRecordWithDuration;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;
use Activity\Duration;
use Activity\Registry\ActivitySprintWithDurationRegistry;
use Activity\Settings;
use DateTime;
use DateTimeZone;

/**
 * Takes care of the very first inactivity which happens from 00:00 up to the time the first record is found.
 */
class ActivitySprintOfMorningInactivityRegistry
{
    /** @var ActivitySprintWithDuration */
    private $data;

    public function __construct(ActivitySprintWithDurationRegistry $sprintRegistry, ActivityRecordInterface $currentActivityRecord)
    {
        $earlyMidnight = DateTime::createFromFormat('Y-m-d H:i:s', $currentActivityRecord->getDateTime()->format('Y-m-d 00:00:00'), new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP));

        if ($earlyMidnight->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP) === $currentActivityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)) {
            // No need to add the initial morning activity record as the first record matches it

            // Add the current record with artificial duration
            $reset = new ActivityRecordAndSprintReset($currentActivityRecord);
            $activitySprintWithArtificialDurationOfNewActivity = new ActivitySprintWithDuration(
                $currentActivityRecord,
                $reset->getActivityRecordWithDurationArtificial(),
                null
            );
            $this->data = $activitySprintWithArtificialDurationOfNewActivity;

            return;
        }

        $veryFirstActivityRecordWithDurationOnInactivity = new ActivityRecordWithDuration(
            new ActivityRecordOnPowerOff($earlyMidnight),
            new Duration(
                $earlyMidnight,
                $currentActivityRecord->getDateTime()
            )
        );

        // The very first sprint initialization (oh man, you should sleep at this time)
        $veryFirstSprintWithDuration = new ActivitySprintWithDuration(
            $veryFirstActivityRecordWithDurationOnInactivity->getActivityRecord(),
            $veryFirstActivityRecordWithDurationOnInactivity,
            $currentActivityRecord
        );
        $sprintRegistry->add($veryFirstSprintWithDuration);

        // Add the current record with artificial duration
        $reset = new ActivityRecordAndSprintReset($currentActivityRecord);
        $activitySprintWithArtificialDurationOfNewActivity = new ActivitySprintWithDuration(
            $currentActivityRecord,
            $reset->getActivityRecordWithDurationArtificial(),
            null
        );

        $this->data = $activitySprintWithArtificialDurationOfNewActivity;
    }

    public function getData(): ActivitySprintWithDuration
    {
        return $this->data;
    }
}
