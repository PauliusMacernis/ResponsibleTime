<?php
declare(strict_types=1);

namespace ResponsibleTime\UserDateTime;

use DateTimeInterface;
use ResponsibleTime\Settings;
use RuntimeException;

class UserDateTimeValidation
{
    public function validate(DateTimeInterface $userDateTimeStart, DateTimeInterface $userDateTimeEnd): void
    {
        if ($userDateTimeStart > $userDateTimeEnd) {
            throw new RuntimeException(sprintf('User requests analysis of records that are of incorrect format. Requested period start value (%s) cannot be after requested period end value (%s).', $userDateTimeStart->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP), $userDateTimeEnd->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)));
        }
    }
}
