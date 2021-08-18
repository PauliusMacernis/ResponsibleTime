<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline;

use DateTimeInterface;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Debug\Debug;
use ResponsibleTime\Timeline\Projects\TimelineOfProjects;

/**
 * Aka. Timeframe
 */
class Timeline
{
    /**
     * TimelineItem The item to be stored temporarily.
     * We sometimes do need this, e.g. when we have a record with a start datetime but not an end datetime, e.g. "current".
     */
    private ?TimelineItem $itemPreliminary;

    /** @var TimelineItem[] */
    private array $items;

    private TimelineOfProjects $timelineOfProjects;

    public function __construct(TimelineOfProjects $timelineOfProjects) {
        $this->timelineOfProjects = $timelineOfProjects;
    }

    public function addItemPreliminary(ActivityRecordInterface $activityRecord, DateTimeInterface $from, DateTimeInterface $to): void
    {
        $this->itemPreliminary = new TimelineItem($activityRecord, $from, $to);
    }

    public function getItemPreliminary(): TimelineItem
    {
        return $this->itemPreliminary;
    }

    public function isSetItemPreliminary(): bool
    {
        return $this->itemPreliminary !== null;
    }

    private function resetItemPreliminary(): void
    {
        $this->itemPreliminary = null;
    }

    public function savePreliminaryItemToTimeline(): void
    {
        Debug::echoRecordWithFromAndToDateTimes($this->getItemPreliminary()->getActivityRecord(), $this->getItemPreliminary()->getFrom(), $this->getItemPreliminary()->getTo());

        $activityRecordTimeLineItem = new TimelineItem(
            $this->getItemPreliminary()->getActivityRecord(),
            $this->getItemPreliminary()->getFrom(),
            $this->getItemPreliminary()->getTo(),
        );

        $lastItemSaved = null;
        if(!empty($this->items)) {
            $lastItemSaved = end($this->items);
        }

        $this->streamToTimelineOfProjectsBeforeRegisteringTheItem($activityRecordTimeLineItem, $lastItemSaved);

        $this->items[] = $activityRecordTimeLineItem;
        $this->resetItemPreliminary();
    }

    private function streamToTimelineOfProjectsBeforeRegisteringTheItem(TimelineItem $activityRecordTimeLineItem, ?TimelineItem $lastItemSaved)
    {
        $this->timelineOfProjects->consumeItem($activityRecordTimeLineItem, $lastItemSaved);
    }

    public function getTimelineOfProjects(): TimelineOfProjects
    {
        return $this->timelineOfProjects;
    }
}
