<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

use Activity\ActivityRecord\ActivityRecord;
use Activity\ActivityRecord\ActivityRecordOnPowerOff;
use Activity\Duration;
use Activity\Records\Records;
use Activity\Settings;

$fileToRead = '/home/paulius/.responsible-time/activities/2020-08-28 (copy).txt';
$fileToWrite = $fileToRead . '.unique';

$records = new Records($fileToRead);
$activityRecordPrevious = null;
$durationBetweenPrevAndThisActivityRecord = null;

foreach ($records as $activityRecord) {

    if (null === $activityRecordPrevious) { // First record
        $durationBetweenPrevAndThisActivityRecord = new Duration($activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP), $activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP));
    } else {
        $durationBetweenPrevAndThisActivityRecord = new Duration($activityRecordPrevious->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP), $activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP));
    }


    $activityRecordPrevious = $activityRecord;
}

//
//die();
//
//$outputToFile = new OutputToFile();
//
//$file = new SplFileObject($fileToRead);
//
//$countRecords = 0;
//$countRecordsUnique = 0;
//$countRecordsInactivity = 0;
//$countRecordsOnPowerOff = 0;
//
//$recordsOnPowerOff = [];
//
//$dateTimeOfActivityStart = null;
//$lastRecordDateTimeExcluded = null;
//
//// -- Registry
//// Activity record
//$activityRecordPrevious = null;
//$activityRecord = null;
//$durationBetweenPrevAndThisActivityRecord = null;
//// Activity
//$activityPrevious = null;
//$activity = null;
//
//
//while ($lineFromFile = $file->fgets()) {
//    ++$countRecords;
//
//    $activityRecord = new ActivityRecord($lineFromFile);
//    $activity = $activityRecord;
//
//    if (null === $activityRecordPrevious) { // First record
//
//        $durationBetweenPrevAndThisActivityRecord = new Duration($activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP), $activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP));
//
//        $outputToFile->outputActivityRecord($activityRecord, $fileToWrite);
//        ++$countRecordsUnique;
//        $dateTimeOfActivityStart = $activityRecord->getDateTime();
//        $lastRecordDateTimeExcluded = $activityRecord->getRecordFollowingDateTime();
//
//    } else { // Later record (any after the first one)
//        // Intermediate activity record needed? For example, power outage, power off,
//        // too long on the same application (probably not near by the computer anymore, - check webcam + AI? :D), etc.
//        $durationBetweenPrevAndThisActivityRecord = new Duration($activityRecordPrevious->getDateTime(), $activityRecord->getDateTime());
//
//        if ($durationBetweenPrevAndThisActivityRecord->getDurationInSeconds() > Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS) {
//            // Append extra "Power off" activity (actually, inactivity) after Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS has passed since the last activity started
//            $outputToFile->outputActivityRecord(new ActivityRecordOnPowerOff($activityRecord), $fileToWrite);
//            ++$countRecordsOnPowerOff;
//        }
//    }
//
//    if (null !== $activityRecordPrevious && $lastRecordDateTimeExcluded !== $activityRecord->getRecordFollowingDateTime()) {
//        $outputToFile->outputActivityRecord($activityRecord, $fileToWrite);
//        ++$countRecordsUnique;
//        $dateTimeOfActivityStart = $activityRecord->getDateTime();
//        $lastRecordDateTimeExcluded = $activityRecord->getRecordFollowingDateTime();
//        $activityPrevious = $activityRecord;
//    }
//
////    var_dump($durationFromPrevToThisRecord, $durationFromPrevToThisRecord->getDurationInSeconds());
////    if ($countRecords === 15) die;
//
//    if (false === $activityRecord->isUserActivity()) {
//        ++$countRecordsInactivity;
//    }
//
//
//    $activityRecordPrevious = $activityRecord;
//    unset($activityRecord);
//}
//
//$file = null;
//
//
//echo "\n" . sprintf('Records in total: %s', $countRecords);
//echo "\n" . sprintf('Inactivity records: %s, or %s percents of total', $countRecordsInactivity, round($countRecordsInactivity * 100 / $countRecords));
//echo "\n" . sprintf('Power off records generated: %s, or %s percents of total', $countRecordsOnPowerOff, round($countRecordsOnPowerOff * 100 / $countRecords));
//echo "\n" . sprintf('Unique activity records: %s, or %s percents of total', $countRecordsUnique, round($countRecordsUnique * 100 / $countRecords));
//echo "\n";