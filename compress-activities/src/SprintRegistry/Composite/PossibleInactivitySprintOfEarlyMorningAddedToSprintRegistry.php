<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintRegistry\Composite;

use DateInterval;
use DateTime;
use DateTimeZone;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Activity\Record\ActivityRecordOnGuessableActivityPrevious;
use ResponsibleTime\Activity\Record\ActivityRecordOnPowerOff;
use ResponsibleTime\Activity\RecordWithDuration\ActivityRecordWithDuration;
use ResponsibleTime\Duration\Duration;
use ResponsibleTime\Settings;
use ResponsibleTime\Sprint\Reset\ActivityRecordAndSprintReset;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

/**
 * Takes care of the very first inactivity which happens from 00:00 up to the time the first record is found.
 */
class PossibleInactivitySprintOfEarlyMorningAddedToSprintRegistry
{
    /** @var SprintRegistryRecord */
    private $data;

    public function __construct(SprintRegistry $sprintRegistry, ActivityRecordInterface $currentActivityRecord)
    {
        $earlyMidnightDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s.u', $currentActivityRecord->getDateTime()->format('Y-m-d\T00:00:00.000000'), new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP));
        $activitySprintWithArtificialDurationOfNewActivity = null;
        $activityRecordToAddLaterOn = $currentActivityRecord;

        // TODO: Test
        if ($earlyMidnightDateTime === $currentActivityRecord->getDateTime()) {
            // No need to add the initial morning activity record as the first record matches it
        }

        $firstPossibleActivityDateTimeEnd = $this->getFirstPossibleMorningActivityDateTimeEnd($earlyMidnightDateTime);

        // @TODO: Test
        if (
            $earlyMidnightDateTime < $currentActivityRecord->getDateTime()
            && $firstPossibleActivityDateTimeEnd > $currentActivityRecord->getDateTime()
        ) {
            // Extend the start date of the first activity as it seems like the same activity running since the very midnight (00:00:00)
            $currentActivityWithResetDateTime = new ActivityRecordOnGuessableActivityPrevious($currentActivityRecord);
            $currentActivityWithResetDateTime->resetDateTime();
            $activityRecordToAddLaterOn = $currentActivityWithResetDateTime;
        }

        // @TODO: Test
        if ($firstPossibleActivityDateTimeEnd < $currentActivityRecord->getDateTime()) {
            // Detected inactivity is longer than MAX_ACTIVITY_RECORD_TIME_IN_SECONDS therefore we guess computer was off.
            $veryFirstActivityRecordWithDurationOnInactivity = new ActivityRecordWithDuration(
                new ActivityRecordOnPowerOff($earlyMidnightDateTime),
                new Duration(
                    $earlyMidnightDateTime,
                    $currentActivityRecord->getDateTime()
                )
            );

            // The very first sprint initialization (oh man, you should sleep at this time)
            $veryFirstSprintWithDuration = new SprintRegistryRecord(
                $veryFirstActivityRecordWithDurationOnInactivity->getActivityRecord(),
                $veryFirstActivityRecordWithDurationOnInactivity,
                $currentActivityRecord
            );
            $sprintRegistry->add($veryFirstSprintWithDuration);

            $activityRecordToAddLaterOn = $currentActivityRecord;
        }

        $this->data = $this->getCurrentRecordWithArtificialDurationAddedToActivitySprint($activityRecordToAddLaterOn);
    }

    public function getData(): SprintRegistryRecord
    {
        return $this->data;
    }

    private function getFirstPossibleMorningActivityDateTimeEnd(DateTime $earlyMidnight): DateTime
    {
        $firstPossibleActivityDateTimeEnd = clone $earlyMidnight;
        $firstPossibleActivityDateTimeEnd->add(new DateInterval(sprintf('PT%sS', Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS)));
        return $firstPossibleActivityDateTimeEnd;
    }

    private function getCurrentRecordWithArtificialDurationAddedToActivitySprint(ActivityRecordInterface $currentActivityRecord): SprintRegistryRecord
    {
        $reset = new ActivityRecordAndSprintReset($currentActivityRecord);
        $activitySprintWithArtificialDurationOfNewActivity = new SprintRegistryRecord(
            $currentActivityRecord,
            $reset->getActivityRecordWithDurationArtificial(),
            null
        );
        return $activitySprintWithArtificialDurationOfNewActivity;
    }
}
