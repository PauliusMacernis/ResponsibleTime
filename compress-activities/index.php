<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

use Activity\ActivityRecordConversionToActivitySprint;
use Activity\Decision\IsActivityRecordExceedingTimeLimit;
use Activity\Records\Records;
use Activity\Registry\ActivitySprintWithDurationRegistry;
use Activity\Registry\Composite\ActivitySprintOfEveningInactivityRegistry;
use Activity\Registry\Composite\ActivitySprintOfMorningInactivityRegistry;
use Activity\Registry\Composite\ActivitySprintWithArtificialDurationOfPreviousActivityAddedToSprintRegistry;
use Activity\Settings;

$dateTimeEarlyMidnight = DateTime::createFromFormat('Y-m-d H:i:s', '2020-08-28 00:00:00', new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP));

$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . ' (copy).txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min.txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min2.txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min-min.txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min3.txt';
$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-test.txt';
$fileToWrite = $fileToRead . '.unique';

$records = new Records($fileToRead);
$sprintRegistry = new ActivitySprintWithDurationRegistry();

$isFirstActivityRecord = true;

$activitySprintWithArtificialDurationOfPreviousActivity = null;
foreach ($records as $currentActivityRecord) {

    if (null === $activitySprintWithArtificialDurationOfPreviousActivity) { // First item, not much to do as the real duration of the record is unknown (missing essential data, artificial data applied).

        $activitySprintWithArtificialDurationOfPreviousActivity = (new ActivitySprintOfMorningInactivityRegistry(
            $sprintRegistry,
            $currentActivityRecord
        ))->getData();

        $activitySprintWithArtificialDurationOfPreviousActivity = (new ActivityRecordConversionToActivitySprint($sprintRegistry, $currentActivityRecord, $activitySprintWithArtificialDurationOfPreviousActivity))->getActivitySprintWithDuration();
        continue;
    }

    if ($activitySprintWithArtificialDurationOfPreviousActivity->getActivityRecordThatStartedSprint()->getDateTime() > $currentActivityRecord->getDateTime()) {
        throw new RuntimeException(sprintf('DateTime anomaly in activity records detected. Activity records must be ordered oldest-to-newest, found a record violating the law: %s', $currentActivityRecord->__toString()));
    }

    $testOnActivityLength = new IsActivityRecordExceedingTimeLimit($activitySprintWithArtificialDurationOfPreviousActivity, $currentActivityRecord);
    if ($testOnActivityLength->isActivityRecordExceedingTimeLimit()) {
        $activitySprintWithArtificialDurationOfPreviousActivity = (new ActivitySprintWithArtificialDurationOfPreviousActivityAddedToSprintRegistry(
            $sprintRegistry,
            $activitySprintWithArtificialDurationOfPreviousActivity,
            $testOnActivityLength,
            $currentActivityRecord
        ))->getData();
        continue;
    }

    $activitySprintWithArtificialDurationOfPreviousActivity = (new ActivityRecordConversionToActivitySprint($sprintRegistry, $currentActivityRecord, $activitySprintWithArtificialDurationOfPreviousActivity))->getActivitySprintWithDuration();
}

if (isset($currentActivityRecord)) { // $currentActivityRecord is actually the last activity record, if any records were at all
    $activitySprintWithArtificialDurationOfLastActivity = (
        new ActivitySprintOfEveningInactivityRegistry(
            $sprintRegistry,
            $currentActivityRecord
        )
    )->getData();
}
