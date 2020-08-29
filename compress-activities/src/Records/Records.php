<?php
declare(strict_types=1);

// Patterns (architecture, naming, etc.) in the software are discovered after the practice interrupted by observation, not created. Therefore class names changes over the time.
// More monitors around you is better for your neck muscles, nerves, etc.
namespace Activity\Records;

use Activity\ActivityRecord\ActivityRecord;
use Iterator;
use SplFileObject;

class Records implements Iterator
{
    private $file;
    private $key;
    private $valueRaw;

    public function __construct(string $fileToRead)
    {
        $this->file = new SplFileObject($fileToRead);
    }

    public function current(): ActivityRecord
    {
        return new ActivityRecord($this->valueRaw, $this->getLineNumber());
    }

    public function next(): void
    {
        ++$this->key;
        if(false === $this->file->eof()) {
            $this->valueRaw = $this->file->fgets();
        } else {
            $this->valueRaw = null;
        }

    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return
            null !== $this->valueRaw
        ;
    }

    public function rewind(): void
    {
        $this->file->rewind();

        $this->key = 0;
        $this->valueRaw = $this->file->fgets();
    }

    private function getLineNumber(): int
    {
        return $this->key + 1;
    }
}
