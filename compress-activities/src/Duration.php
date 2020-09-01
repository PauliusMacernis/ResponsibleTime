<?php
declare(strict_types=1);

namespace Activity;

use DateTimeInterface;

class Duration
{
    private $dateTimeStart;
    private $dateTimeEnd;

    public function __construct(DateTimeInterface $dateTimeStart, DateTimeInterface $dateTimeEnd)
    {
        $this->dateTimeStart = $dateTimeStart;
        $this->dateTimeEnd = $dateTimeEnd;
    }

    public function getDurationInSeconds(): int
    {
        return $this->dateTimeEnd->getTimestamp() - $this->dateTimeStart->getTimestamp();
    }

    public function getDateTimeEnd(): DateTimeInterface
    {
        return $this->dateTimeEnd;
    }

    public function getDateTimeStartFormatted(): string
    {
        return $this->getFormattedDateTime($this->dateTimeStart);
    }

    public function getDateTimeEndFormatted(): string
    {
        return $this->getFormattedDateTime($this->dateTimeEnd);
    }

    private function getFormattedDateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP);
    }
}
