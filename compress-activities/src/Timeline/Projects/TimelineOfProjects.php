<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline\Projects;

use ResponsibleTime\Timeline\TimelineItem;

class TimelineOfProjects
{
    private $items;

    public function consumeItem(TimelineItem $activityRecordTimeLineItem)
    {
        $projectItem = new TimelineOfProjectsItem($activityRecordTimeLineItem);
        if (false === $projectItem->isConsumableItem()) {
            $this->reactOnItemInconsumable($projectItem);

            return;
        }

        $this->reactOnItemConsumable($projectItem);
    }

    private function reactOnItemInconsumable(TimelineOfProjectsItem $projectItem)
    {
        echo "--- Inconsumable ---" . PHP_EOL;
    }

    private function reactOnItemConsumable(TimelineOfProjectsItem $projectItem)
    {
        echo "+++ Consumable +++" . PHP_EOL;
    }
}
