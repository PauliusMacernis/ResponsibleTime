<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline\Projects;

use DateTimeInterface;
use ResponsibleTime\Timeline\TimelineItem;

class TimelineOfProjects
{
    /** @var TimelineOfProjectsItemAbstract[] */
    private array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    public function consumeItem(TimelineItem $activityRecordTimeLineItemToConsume, ?TimelineItem $lastItemConsumed)
    {
        $projectItem = new TimelineOfProjectsItem($activityRecordTimeLineItemToConsume);
        $projectItemLast = isset($lastItemConsumed) ? new TimelineOfProjectsItem($lastItemConsumed) : null;

        $isThisItemConsumable = $projectItem->isConsumable();
        $isThisVeryFirstRecord = isset($lastItemConsumed) === false;
        $isPreviousItemConsumable = isset($projectItemLast) && $projectItemLast->isConsumable();

        if (false === $isThisItemConsumable) {
            $this->reactOnItemInconsumable($activityRecordTimeLineItemToConsume, $isThisVeryFirstRecord, $isPreviousItemConsumable);

            return;
        }

        $this->reactOnItemConsumable($projectItem);
    }

    private function reactOnItemInconsumable(TimelineItem $timelineItem, bool $isThisVeryFirstRecord, bool $isPreviousItemConsumable)
    {
        echo "--- Inconsumable ---" . PHP_EOL;

        if($isThisVeryFirstRecord) {
            $this->addInactivityArtificial($timelineItem);

            return;
        }

        if($isPreviousItemConsumable) {
            $this->extendPreviousActivityUpToEndDateTimeOf($timelineItem);
            $this->addInactivityArtificial($timelineItem);

            return;
        }

        $this->extendPreviousActivityUpToEndDateTimeOf($timelineItem);

    }

    private function reactOnItemConsumable(TimelineOfProjectsItem $projectItem)
    {
        echo "+++ Consumable +++" . PHP_EOL;

        if($this->isThisVeryFirstRecord()) {
            $this->addNewActivityStarted($projectItem);

            return;
        }

        if($this->isTheLastOneRegisteredActivityTheSameTaskAs($projectItem)) {
            $this->extendPreviousActivityUpToEndDateTimeOf($projectItem->getTimelineItem());
        } else {
            $this->addNewActivityStarted($projectItem);
        }
    }

    private function isThisVeryFirstRecord(): bool
    {
        return empty($this->items);
    }

    private function addInactivityArtificial(TimelineItem $timelineItem): void
    {
        $this->items[] = new TimelineOfProjectsItemInactivity($timelineItem);
    }

    private function extendPreviousActivityUpToEndDateTimeOf(TimelineItem $timelineItem): void
    {
        /** @var TimelineOfProjectsItemAbstract $lastItem */
        $lastItem = array_pop($this->items); // get the last item of array by removing it from the list
        $lastItem->setDateTimeEnd($timelineItem->getTo());

        if(null === $this->items) { // if the only item has been popped out just a moment ago
            $this->items = [];
        } else {
            end($this->items); // get the cursor back to the end of the array because array_pop did reset it
        }
        $this->items[] = $lastItem;
    }

    private function addNewActivityStarted(TimelineOfProjectsItem $projectItem): void
    {
        $this->items[] = $projectItem;
    }

    private function isTheLastOneRegisteredActivityTheSameTaskAs(TimelineOfProjectsItem $projectItem)
    {
        /** @var TimelineOfProjectsItemAbstract $lastItem */
        $lastItem = end($this->items);
        return $lastItem->isTheSameProjectTaskAs($projectItem);
    }

    private function isPreviousItemEndingAt(DateTimeInterface $dateTime): bool
    {
        /** @var TimelineOfProjectsItemAbstract $lastItem */
        $lastItem = end($this->items);
        return $lastItem->getDateTimeEnd()->getTimestamp() === $dateTime->getTimestamp();
    }

}
