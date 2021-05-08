<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Files;

use DateInterval;
use DatePeriod;
use DateTimeInterface;
use Iterator;
use ResponsibleTime\Settings;

/**
 * Iterates files containing activity records starting from the earliest to the latest day files.
 *
 * @TODO: Validate filenames while iterating, not in the constructor. This way the very latest data may be analyzed too.
 */
class Files implements Iterator
{
    private $filePaths;
    private $key;


    public function __construct(DateTimeInterface $utcDateTimePeriodStart, DateTimeInterface $utcDateTimePeriodEnd)
    {
        $datePeriod = new DatePeriod(
            $utcDateTimePeriodStart,
            new DateInterval('P1D'),
            $utcDateTimePeriodEnd
        );

        $this->filePaths = [];
        foreach($datePeriod as $dateTime) {
            $fileName = $this->getFilePathOutOfDateTime($dateTime);
            if (false === $this->isFile($fileName)) {
                continue;
            }
            $this->filePaths[] = $fileName;
        }

        // Make sure the end day exists in the array as it may not be there, in case there is no P1D gap between start-end dates.
        $fileNameOfUtcDateTimePeriodEnd = $this->getFilePathOutOfDateTime($utcDateTimePeriodEnd);

        if ($this->isFile($fileNameOfUtcDateTimePeriodEnd) && false === in_array($fileNameOfUtcDateTimePeriodEnd, $this->filePaths, true)) {
            $this->filePaths[] = $fileNameOfUtcDateTimePeriodEnd;
        }
    }

    private function isFile(string $fileName): bool
    {
        return file_exists($fileName) && is_readable($fileName);
    }

    private function getFilePathOutOfDateTime(DateTimeInterface $dateTime): string
    {
        return Settings::DIR_OF_ACTIVITY_RECORDS . $dateTime->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS);
    }


    public function current()
    {
        return $this->filePaths[$this->key];
    }

    public function next(): void
    {
        ++$this->key;
    }

    public function key()
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return array_key_exists($this->key, $this->filePaths);
    }

    public function rewind(): void
    {
        reset($this->filePaths);
        $this->key = 0;
    }
}
