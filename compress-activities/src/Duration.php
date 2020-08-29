<?php
declare(strict_types=1);

namespace Activity;

use DateTime;

class Duration
{
    private $timeStart;
    private $timeEnd;

    public function __construct(string $timeStartString, string $timeEndString)
    {
        $this->timeStart = new DateTime($timeStartString);
        $this->timeEnd = new DateTime($timeEndString);
    }

    public function getDurationInSeconds(): int
    {
        return $this->timeEnd->getTimestamp() - $this->timeStart->getTimestamp();
    }
}
