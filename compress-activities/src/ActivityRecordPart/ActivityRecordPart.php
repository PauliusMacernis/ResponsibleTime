<?php
declare(strict_types=1);

namespace Activity\ActivityRecordPart;

/**
 * Each of activity record parts extend from this class. Excluding the DateTime class because
 * it should inherit \DateTime functionalities while there is no native "multi-extend" in order to extend from a few classes in PHP.
 *
 * NOTICE: DateTime part do not extend from this class as you may think at first.
 * @see: DateTime under the same namespace.
 */
abstract class ActivityRecordPart
{
    protected $data;

    public function __construct(string $partOfTheFileLineMatchingPattern)
    {
        $this->data = trim($partOfTheFileLineMatchingPattern);
    }

    public function __toString(): string
    {
        return $this->data;
    }
}
