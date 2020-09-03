<?php
declare(strict_types=1);

namespace ResponsibleTime\SprintWithDuration\RecordWithDuration\First;

use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\SprintRegistry\Composite\PossibleMorningInactivitySprintAddedToSprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintRegistry\SprintRegistryRecord;
use ResponsibleTime\SprintWithDuration\Sprint;

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
            new PossibleMorningInactivitySprintAddedToSprintRegistry(
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
