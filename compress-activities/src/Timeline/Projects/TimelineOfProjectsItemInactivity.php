<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline\Projects;

use ResponsibleTime\Timeline\TimelineItem;

final class TimelineOfProjectsItemInactivity extends TimelineOfProjectsItemAbstract
{
    private const INACTIVITY_PROJECT_TITLE = 'INACTIVITY';
    private const INACTIVITY_TASK_TITLE = 'INACTIVITY';
    private const INACTIVITY_TYPE_TITLE = 'INACTIVITY';

    public function __construct(TimelineItem $timelineItem)
    {
        $this->timelineItem = $timelineItem;
        $this->projectTitle = self::INACTIVITY_PROJECT_TITLE;
        $this->taskTitle = self::INACTIVITY_TASK_TITLE;
        $this->activityTypeTitle = self::INACTIVITY_TYPE_TITLE;
        $this->setDateTimeEnd($timelineItem->getTo());
    }
}
