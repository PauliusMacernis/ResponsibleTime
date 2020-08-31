<?php
declare(strict_types=1);

namespace Activity\ActivitySprintWithDuration;

use Activity\ActivityRecord\ActivityRecordInterface;
use Activity\ActivityRecordWithDuration\ActivityRecordWithDuration;
use Activity\Duration;
use Activity\Settings;
use RuntimeException;

class ActivitySprintWithDuration
{
    /** @var ActivityRecordInterface */
    private $activityRecordThatStartedSprint;

    /** @var ActivityRecordWithDuration|null */
    private $activityRecordThatCompletedSprintWithArtificialDateTime;

    /** @var ActivityRecordInterface|null */
    private $upcomingAnotherSprintFirstActivity;

    /**
     * @var Duration Duration from activity record that starts the sprint up to the activity record that ends it (which actually the activity of the new sprint already, therefore )
     * In case Activity that ends the sprint is unknown, the duration is calculated by taking the max allowed duration for the activity record
     */
    private $activitySprintDuration;

    public function __construct(ActivityRecordInterface $activityRecordThatStartsSprint, ?ActivityRecordWithDuration $activityRecordThatCompletedSprintWithArtificialDateTime, ?ActivityRecordInterface $upcomingAnotherSprintFirstActivity)
    {
        if (null === $activityRecordThatCompletedSprintWithArtificialDateTime && null === $upcomingAnotherSprintFirstActivity) {
            throw new RuntimeException('Activity sprint MUST RECEIVE the most recent activity of the same sprint OR the first activity of the new sprint WHEN constructing the object.');
        }

        $this->activityRecordThatStartedSprint = $activityRecordThatStartsSprint;
        $this->activityRecordThatCompletedSprintWithArtificialDateTime = $activityRecordThatCompletedSprintWithArtificialDateTime;
        $this->upcomingAnotherSprintFirstActivity = $upcomingAnotherSprintFirstActivity;

        $dateTimeStart = $activityRecordThatStartsSprint->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP); // @TODO: Drop format ? By moving it to the farrest place possible..
        if (null === $upcomingAnotherSprintFirstActivity) {
            $dateTimeEnd = $activityRecordThatCompletedSprintWithArtificialDateTime->getActivityRecordDuration()->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP);
        } else {
            $dateTimeEnd = $upcomingAnotherSprintFirstActivity->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP);
        }

        $this->activitySprintDuration = new Duration($dateTimeStart, $dateTimeEnd);
    }

    public function getActivityRecordThatStartedSprint(): ActivityRecordInterface
    {
        return $this->activityRecordThatStartedSprint;
    }

    public function getActivitySprintDuration(): Duration
    {
        return $this->activitySprintDuration;
    }

    public function getActivityRecordThatCompletedSprintWithArtificialDateTime(): ?ActivityRecordWithDuration
    {
        return $this->activityRecordThatCompletedSprintWithArtificialDateTime;
    }

    public function getUpcomingAnotherSprintFirstActivity(): ?ActivityRecordInterface
    {
        return $this->upcomingAnotherSprintFirstActivity;
    }
}
