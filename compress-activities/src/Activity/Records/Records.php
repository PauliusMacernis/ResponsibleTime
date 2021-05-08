<?php
declare(strict_types=1);

// Guru says:
// Patterns (architecture, naming, etc.) in the software are discovered after the practice interrupted by observation, not created. Therefore class names changes over the time.
// More monitors around you is better for your neck muscles, nerves, etc.

namespace ResponsibleTime\Activity\Records;

use Exception;
use Iterator;
use ResponsibleTime\Activity\Record\ActivityRecord;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Exception\InvalidActivityRecordException;
use SplFileObject;

class Records implements Iterator
{
    private ?SplFileObject $file;

    private int $key;

    /**
     * A line from file
     * @link https://php.net/manual/en/splfileobject.fgets.php
     * @var string|false a string containing the next line from the file, or false on error.
     */
    private $valueRaw;

    private ?ActivityRecordInterface $valueObject;

    public function __construct(string $fileToRead)
    {
        try {
            $this->file = new SplFileObject($fileToRead);
        } catch (Exception $exception){
            // In case a file with activity records of a day does not exist
            $this->file = null;
        }
    }

    public function current(): ?ActivityRecordInterface
    {
        try {
            return new ActivityRecord($this->valueRaw, $this->getLineNumber(), $this->file);
        } catch (InvalidActivityRecordException $exception) {
            return null;
        }
    }

    public function next(): void
    {
        ++$this->key;
        $this->valueRaw = $this->file->fgets();
        $this->setValueObjectOrNullFromValueRaw();
    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        while ($this->valueObject === null && false === $this->file->eof()) {
            // Skip items that cannot be resolved to objects, e.g. empty lines, broken formats, etc.
            $this->next();
        }

        return
            null !== $this->valueObject;
    }

    public function rewind(): void
    {
        if(null !== $this->file) {
            $this->file->rewind();
            $this->key = 0;
            $this->valueRaw = $this->file->fgets();
            $this->setValueObjectOrNullFromValueRaw();
        }
    }

    private function getLineNumber(): int
    {
        return $this->key + 1;
    }

    private function setValueObjectOrNullFromValueRaw(): void
    {
        try {
            $this->valueObject = new ActivityRecord($this->valueRaw, $this->getLineNumber(), $this->file);
        } catch (InvalidActivityRecordException $exception) {
            $this->valueObject = null;
        }
    }
}
