<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

use ResponsibleTime\Activity\Decision\IsActivityRecordExceedingTimeLimit;
use ResponsibleTime\Activity\Records\Records;
use ResponsibleTime\Settings;
use ResponsibleTime\SprintRegistry\SprintRegistry;
use ResponsibleTime\SprintWithDuration\RecordWithDuration\First\FirstRecordAddedToSprintRegistry;
use ResponsibleTime\SprintWithDuration\RecordWithDuration\Last\LastRecordAddedToSprintRegistry;
use ResponsibleTime\SprintWithDuration\RecordWithDuration\MiddleExceedingTimeLimit\MiddleRecordExceedingTimeLimitAddedToSprintRegistry;
use ResponsibleTime\SprintWithDuration\RecordWithDuration\MiddleNotExceedingTimeLimit\MiddleRecordNotExceedingTimeLimitAddedToSprintRegistry;

$dateTimeEarlyMidnight = DateTime::createFromFormat('Y-m-d\TH:i:s.u', '2020-08-28T00:00:00.000000', new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP));

$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . ' (copy).txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min.txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min2.txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min-min.txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-min3.txt';
$fileToRead = '/home/paulius/.responsible-time/activities/' . $dateTimeEarlyMidnight->format(Settings::FILENAME_DATETIME_FORMAT_FOR_RECORDS) . '-test.txt';
$fileToWrite = $fileToRead . '.unique';

$records = new Records($fileToRead);
$sprintRegistry = new SprintRegistry();

$isFirstActivityRecord = true;
$sprintWithPreviousActivityAdded = null;

foreach ($records as $currentActivity) {

    if (null === $sprintWithPreviousActivityAdded) { // First item, not much to do as the real duration of the record is unknown (missing essential data, artificial data applied).
        $sprintWithPreviousActivityAdded = (new FirstRecordAddedToSprintRegistry($sprintRegistry, $currentActivity))->getData();
        continue;
    }

    if ($sprintWithPreviousActivityAdded->getActivityRecordThatStartedSprint()->getDateTime() > $currentActivity->getDateTime()) {
        throw new RuntimeException(sprintf('DateTime anomaly in activity records detected. Activity records must be ordered oldest-to-newest, found a record violating the law: %s', $currentActivity->__toString()));
    }

    $testOnActivityLength = new IsActivityRecordExceedingTimeLimit($sprintWithPreviousActivityAdded, $currentActivity);
    if ($testOnActivityLength->isActivityRecordExceedingTimeLimit()) {
        $sprintWithPreviousActivityAdded = (new MiddleRecordExceedingTimeLimitAddedToSprintRegistry($sprintRegistry, $sprintWithPreviousActivityAdded, $testOnActivityLength, $currentActivity))->getData();
        continue;
    }

    $sprintWithPreviousActivityAdded = (new MiddleRecordNotExceedingTimeLimitAddedToSprintRegistry($sprintRegistry, $currentActivity, $sprintWithPreviousActivityAdded))->getData();
}


if (isset($currentActivity)) { // $currentActivityRecord is actually the last activity record, if any records were at all
    new LastRecordAddedToSprintRegistry($sprintRegistry, $currentActivity);
}
