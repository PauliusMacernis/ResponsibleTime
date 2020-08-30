<?php
declare(strict_types=1);

namespace Activity\ActivitySprintWithDuration;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\Duration;
use Activity\Settings;

class ActivitySprintWithDuration
{
    /** @var ActivityRecordInterface */
    private $activityRecordThatStartedSprint;
    private $activitySprintDuration;

    public function __construct(ActivityRecordInterface $activityRecordThatStartsSprint, Duration $activitySprintDuration)
    {
        $this->activityRecordThatStartedSprint = $activityRecordThatStartsSprint;
        $this->activitySprintDuration = new Duration(
            $activityRecordThatStartsSprint->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
            $activitySprintDuration->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
        );
    }

    public function getActivityRecordThatStartedSprint(): ActivityRecordInterface
    {
        return $this->activityRecordThatStartedSprint;
    }

    public function getActivitySprintDuration(): Duration
    {
        return $this->activitySprintDuration;
    }
}
