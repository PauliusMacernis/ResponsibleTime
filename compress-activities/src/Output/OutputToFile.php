<?php
declare(strict_types=1);

use ResponsibleTime\Activity\Record\ActivityRecordinterface;

/**
 * @TODO: Make it usable or remove. Not in use at the moment.
 */
class OutputToFile
{
//    public function outputActivityRecord(ActivityRecordinterface $record, string $pathToFile): void
//    {
//        if ($record->isUserActivity()) {
//            $lineToWrite = sprintf("%s: %s\n", $record->getDateTime(), $record->getRecordFollowingDateTimeTrimmed());
//        } else {
//            $lineToWrite = sprintf("%s: INACTIVITY: %s\n", $record->getDateTime(), $record->getRecordFollowingDateTimeTrimmed());
//        }
//
//        if (false === file_put_contents($pathToFile, $lineToWrite, FILE_APPEND | LOCK_EX)) {
//            throw new RuntimeException(sprintf('Cannot write to file. File: %s', $pathToFile));
//        }
//    }
}
