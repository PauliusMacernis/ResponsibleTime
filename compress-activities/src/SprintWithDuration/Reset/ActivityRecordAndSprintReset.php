<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintWithDuration\Reset;

use DateInterval;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Activity\RecordWithDuration\ActivityRecordWithDuration;
use ResponsibleTime\Duration\Duration;
use ResponsibleTime\Settings;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class ActivityRecordAndSprintReset
{
    /** @var ActivityRecordWithDuration */
    private $activityRecordWithDurationArtificial;

    /** @var SprintRegistryRecord */
    private $sprintRegistryRecordWithDurationArtificial;

    public function __construct(ActivityRecordInterface $activityRecord)
    {
        $activityRecordDateTimePlusMax = clone $activityRecord->getDateTime();
        $activityRecordDateTimePlusMax->add(new DateInterval(sprintf('PT%sS', Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS))); // @TODO: Should each type of activity record have a different "max activity record in seconds"? It is basically about how fast the mental stack releases the information after different type of application, e.g. terminal - 1 second, php storm - 5 minutes,
        $durationArtificial = new Duration(
            $activityRecord->getDateTime(),
            $activityRecordDateTimePlusMax
        );

        $this->activityRecordWithDurationArtificial = new ActivityRecordWithDuration($activityRecord, $durationArtificial);

        $activityRecordWithDuration = new ActivityRecordWithDuration($activityRecord, $durationArtificial);
        $this->sprintRegistryRecordWithDurationArtificial = new SprintRegistryRecord($activityRecord, $activityRecordWithDuration, null);
    }

    public function getActivityRecordWithDurationArtificial(): ActivityRecordWithDuration
    {
        return $this->activityRecordWithDurationArtificial;
    }

    public function getSprintRegistryRecordWithDurationArtificial(): SprintRegistryRecord
    {
        return $this->sprintRegistryRecordWithDurationArtificial;
    }
}
