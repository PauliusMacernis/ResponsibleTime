<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline;

use DateTimeInterface;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;

class TimelineItem
{
    private ActivityRecordInterface $activityRecord;
    private DateTimeInterface $from;
    private DateTimeInterface $to;

    public function __construct(ActivityRecordInterface $activityRecord, DateTimeInterface $from, DateTimeInterface $to)
    {
        $this->activityRecord = $activityRecord;
        $this->from = $from;
        $this->to = $to;
    }

    public function getActivityRecord(): ActivityRecordInterface
    {
        return $this->activityRecord;
    }

    public function getFrom(): DateTimeInterface
    {
        return $this->from;
    }

    public function getTo(): DateTimeInterface
    {
        return $this->to;
    }
}
