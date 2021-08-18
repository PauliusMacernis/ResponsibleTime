<?php
declare(strict_types=1);

namespace ResponsibleTime\Timeline\Projects;

use DateTimeInterface;
use ResponsibleTime\SettingsProject;
use ResponsibleTime\Timeline\TimelineItem;

class TimelineOfProjectsItem extends TimelineOfProjectsItemAbstract
{
    public function __construct(TimelineItem $timelineItem)
    {
        $projectsSettings = SettingsProject::PROJECTS_WE_TRACK_TIME_ON;

        foreach ($projectsSettings as $projectTitle => $projectSettings)
        {
            // Skip projects that do not have "on" triggers
            if(!isset($projectSettings['on'])) {
                continue;
            }

            foreach($projectSettings['on'] as $activityTypeTitle => $activityPatterns) {
                $isMatching = preg_match(
                    $activityPatterns['WmClass'],
                    $timelineItem->getActivityRecord()->getWmClass()->__toString()
                );
                if(!$isMatching) {
                    // Skip if WmClass does not match
                    continue;
                }

                $windowTitleMatch = [];
                $isMatching = preg_match(
                    $activityPatterns['WindowTitle'],
                    $timelineItem->getActivityRecord()->getWindowTitle()->__toString(),
                    $windowTitleMatch
                );
                if(!$isMatching) {
                    // Skip if WmClass does not match
                    continue;
                }

                $this->timelineItem = $timelineItem;
                $this->projectTitle = $projectTitle;
                $this->taskTitle = (string) reset($windowTitleMatch);
                $this->activityTypeTitle = $activityTypeTitle;

                return; // we just found the project, end the search;

            }
        }
    }

    public function isConsumable(): bool
    {
        return isset($this->timelineItem);
    }

    public function getDateTimeEnd(): DateTimeInterface
    {
        return $this->timelineItem->getTo();
    }

}
