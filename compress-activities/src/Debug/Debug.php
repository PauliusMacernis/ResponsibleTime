<?php
declare(strict_types=1);

namespace ResponsibleTime\Debug;

use DateTimeInterface;
use ResponsibleTime\Activity\Files\Files;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Settings;

class Debug
{
    public static function echoUserDatetimeChoice(DateTimeInterface $requestedUtcDateTimePeriodStart, DateTimeInterface $requestedUtcDateTimePeriodEnd): void
    {
        echo PHP_EOL . PHP_EOL;
        echo '----------------------------------------------------------------------------------------------' . PHP_EOL;
        echo 'User datetime: ' . PHP_EOL
            . $requestedUtcDateTimePeriodStart->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            . ' - '
            . $requestedUtcDateTimePeriodEnd->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            . PHP_EOL;
        echo '----------------------------------------------------------------------------------------------' . PHP_EOL;
        echo PHP_EOL;
    }

    public static function echoFilesToReadForRecords(Files $filesToReadForRecords): void
    {
        echo 'Files: ' . PHP_EOL;
        foreach ($filesToReadForRecords as $fileToRead) {
            echo $fileToRead . ' ' . PHP_EOL;
        }
        echo PHP_EOL . PHP_EOL;
    }

    public static function echoRecordWithFromAndToDateTimes(ActivityRecordInterface $activityRecord, DateTimeInterface $from, DateTimeInterface $to): void
    {
        echo PHP_EOL . PHP_EOL
            . '[' . $from->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP) . ' - ' . $to->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP) . ']'
            . PHP_EOL . $activityRecord->__toString();
    }

    public static function echoMethodName(string $__METHOD__): void
    {
        echo $__METHOD__ . PHP_EOL;
    }
}
