<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintRegistry;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Activity\RecordWithDuration\ActivityRecordWithDuration;
use ResponsibleTime\Duration\Duration;
use RuntimeException;

class SprintRegistryRecord
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

    /** @var ActivityRecordWithDuration */
    private $lastActivityRecordWithDurationUpToDate;

    public function __construct(ActivityRecordInterface $activityRecordThatStartsSprint, ?ActivityRecordWithDuration $activityRecordThatCompletedSprintWithArtificialDateTime, ?ActivityRecordInterface $upcomingAnotherSprintFirstActivity)
    {
        $this->activityRecordThatStartedSprint = $activityRecordThatStartsSprint;
        $this->activityRecordThatCompletedSprintWithArtificialDateTime = $activityRecordThatCompletedSprintWithArtificialDateTime;
        $this->upcomingAnotherSprintFirstActivity = $upcomingAnotherSprintFirstActivity;
        $this->setActivitySprintDuration($activityRecordThatStartsSprint, $upcomingAnotherSprintFirstActivity, $activityRecordThatCompletedSprintWithArtificialDateTime);
        $this->setLastActivityRecordWithDurationUpToDate($activityRecordThatCompletedSprintWithArtificialDateTime, $upcomingAnotherSprintFirstActivity);
    }

    public function getActivityRecordThatStartedSprint(): ActivityRecordInterface
    {
        return $this->activityRecordThatStartedSprint;
    }

    public function getActivityRecordThatCompletedSprintWithArtificialDateTime(): ?ActivityRecordWithDuration
    {
        return $this->activityRecordThatCompletedSprintWithArtificialDateTime;
    }

    public function getUpcomingAnotherSprintFirstActivity(): ?ActivityRecordInterface
    {
        return $this->upcomingAnotherSprintFirstActivity;
    }

    public function getActivitySprintDuration(): Duration
    {
        return $this->activitySprintDuration;
    }

    public function getLastActivityRecordWithDurationUpToDate(): ActivityRecordWithDuration
    {
        return $this->lastActivityRecordWithDurationUpToDate;
    }

    private function setActivitySprintDuration(ActivityRecordInterface $activityRecordThatStartsSprint, ?ActivityRecordInterface $upcomingAnotherSprintFirstActivity, ?ActivityRecordWithDuration $activityRecordThatCompletedSprintWithArtificialDateTime): void
    {
        if (null === $activityRecordThatCompletedSprintWithArtificialDateTime && null === $upcomingAnotherSprintFirstActivity) {
            throw new RuntimeException('Activity sprint MUST RECEIVE the most recent activity of the same sprint OR the first activity of the new sprint WHEN constructing the object with activity SPRINT duration.');
        }

        $dateTimeStart = $activityRecordThatStartsSprint->getDateTime();
        if (null === $upcomingAnotherSprintFirstActivity) {
            $dateTimeEnd = $activityRecordThatCompletedSprintWithArtificialDateTime->getActivityRecordDuration()->getDateTimeEnd();
        } else {
            $dateTimeEnd = $upcomingAnotherSprintFirstActivity->getDateTime();
        }

        $this->activitySprintDuration = new Duration($dateTimeStart, $dateTimeEnd);
    }

    private function setLastActivityRecordWithDurationUpToDate(?ActivityRecordWithDuration $activityRecordThatCompletedSprintWithArtificialDateTime, ?ActivityRecordInterface $upcomingAnotherSprintFirstActivity): void
    {
        if (null === $activityRecordThatCompletedSprintWithArtificialDateTime && null === $upcomingAnotherSprintFirstActivity) {
            throw new RuntimeException('Activity sprint MUST RECEIVE the most recent activity of the same sprint OR the first activity of the new sprint WHEN constructing the object with activity RECORD duration.');
        }

        $dateTimeStart = $activityRecordThatCompletedSprintWithArtificialDateTime->getActivityRecord()->getDateTime();
        if (null === $this->upcomingAnotherSprintFirstActivity) {
            $dateTimeEnd = $activityRecordThatCompletedSprintWithArtificialDateTime->getActivityRecordDuration()->getDateTimeEnd();
        } else {
            $dateTimeEnd = $upcomingAnotherSprintFirstActivity->getDateTime();
        }
        $this->lastActivityRecordWithDurationUpToDate = new ActivityRecordWithDuration($activityRecordThatCompletedSprintWithArtificialDateTime->getActivityRecord(), new Duration($dateTimeStart, $dateTimeEnd));
    }
}
