<?php
declare(strict_types=1);

namespace ResponsibleTime\Sprint\RecordWithDuration\First;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Sprint\Sprint;
use ResponsibleTime\SprintRegistry\Composite\PossibleInactivitySprintOfEarlyMorningAddedToSprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;

class FirstRecordAddedToSprintRegistry
{
    private $data;

    public function __construct(SprintRegistry $sprintRegistry, ActivityRecordInterface $currentActivityRecord)
    {
        $this->data = (
        new Sprint(
            $sprintRegistry,
            $currentActivityRecord,
            (
            new PossibleInactivitySprintOfEarlyMorningAddedToSprintRegistry(
                $sprintRegistry,
                $currentActivityRecord
            )
            )->getData()
        )
        )->getLastSprintRegistryRecordAdded();
    }

    public function getData(): SprintRegistryRecord
    {
        return $this->data;
    }
}
