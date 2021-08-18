<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline\Projects;

use DateTimeInterface;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Timeline\TimelineItem;

abstract class TimelineOfProjectsItemAbstract
{
    protected ?TimelineItem $timelineItem;
    protected string $projectTitle;
    protected string $taskTitle;

    protected string $activityTypeTitle;

    private DateTimeInterface $dateTimeEnd;

    public function setDateTimeEnd(DateTimeInterface $dateTimeEnd): void
    {
        $this->dateTimeEnd = $dateTimeEnd;
    }

    public function isTheSameProjectTaskAs(TimelineOfProjectsItem $projectItem): bool
    {

        return
//            $this->projectTitle === $projectItem->getProjectTitle()
//            && $this->getActivityTypeTitle() === $projectItem->getActivityTypeTitle()
        //    &&
        $this->getActivityRecordFirst()->getWmClass()->__toString() === $projectItem->getActivityRecordFirst()->getWmClass()->__toString()
        && $this->getActivityRecordFirst()->getWindowTitle()->__toString() === $projectItem->getActivityRecordFirst()->getWindowTitle()->__toString()
            //&& $this->taskTitle === $projectItem->getTaskTitle()
        ;
    }

    public function getProjectTitle(): string
    {
        return $this->projectTitle;
    }

    public function getTaskTitle(): string
    {
        return $this->taskTitle;
    }

    public function getActivityTypeTitle(): string
    {
        return $this->activityTypeTitle;
    }

    public function getTimelineItem(): ?TimelineItem
    {
        return $this->timelineItem;
    }

    public function getDateTimeEnd(): DateTimeInterface
    {
        return $this->dateTimeEnd;
    }

    public function getActivityRecordFirst(): ActivityRecordInterface
    {
        return $this->timelineItem->getActivityRecord();
    }
}
