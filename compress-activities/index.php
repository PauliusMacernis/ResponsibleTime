<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

use Activity\ActivityRecordAndSprintReset\ActivityRecordAndSprintReset;
use Activity\ActivityRecordWithDuration\ActivityRecordWithDuration;
use Activity\ActivitySprintWithDuration\ActivitySprintWithDuration;
use Activity\ActivitySprintWithDurationRegistry\ActivitySprintWithDurationRegistry;
use Activity\Decision\IsSameActivity;
use Activity\Duration;
use Activity\Records\Records;
use Activity\Settings;

$fileToRead = '/home/paulius/.responsible-time/activities/2020-08-28 (copy).txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/2020-08-28-min.txt';
$fileToWrite = $fileToRead . '.unique';

$records = new Records($fileToRead);
$sprintRegistry = new ActivitySprintWithDurationRegistry();

$isFirstActivityRecord = true;
foreach ($records as $activityRecord) {

    // First record
    // First record is always incomplete as we need data on the second activity to determine how long the first activity took
    // Therefore the first record always gets "default max duration" (which should be adjusted in case duration is treated to be completed ?? !!!!!!! )
    // --- ACTIVITY DECISIONS ---
    $reset = new ActivityRecordAndSprintReset($activityRecord);
    if (true === $isFirstActivityRecord) {
        $activityRecordWithDuration = $reset->getActivityRecordWithDuration();
        $activitySprintWithDuration = $reset->getActivitySprintWithDuration();
    } else { // Any other line but the first one
        // We know the next item so we adjust the duration of the previous activity (& so the sprint too!) to be up to the start of the current activity.
        $activityRecordWithDuration = new ActivityRecordWithDuration(
            $activityRecordWithDuration->getActivityRecord(),
            new Duration(
                $activityRecordWithDuration->getActivityRecordDuration()->getTimeStart()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
                $reset->getActivityRecordWithDuration()->getActivityRecordDuration()->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            )
        );

        //var_dump($reset->getActivityRecordWithDuration()->getActivityRecordDuration(), $activityRecord); die();

        $activitySprintWithDuration = new ActivitySprintWithDuration(
            $activitySprintWithDuration->getActivityRecordThatStartedSprint(),
            new Duration(
                $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
                $reset->getActivityRecordWithDuration()->getActivityRecordDuration()->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP) //// @TODO: Plus MAX
            )
        );
    }

    // --- ACTIVITY SPRINT DECISIONS ---
    $isSameActivity = (new IsSameActivity($activitySprintWithDuration, $activityRecord))->isSameActivity();

    // In case it is the same type of activity, we add up the time so at the end we have duration: FROM first activity of the type (start) TO this activity start+max activity duration possible (end).
    if (true === $isSameActivity) {
        $activitySprintWithDuration = new ActivitySprintWithDuration(
            $activitySprintWithDuration->getActivityRecordThatStartedSprint(),
            new Duration(
                $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
                $reset->getActivityRecordWithDuration()->getActivityRecordDuration()->getTimeEnd()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            )
        );
    }

    // In case this is the activity starting the new sprint
    if (false === $isSameActivity) {
        $activitySprintWithDuration = new ActivitySprintWithDuration(
            $activitySprintWithDuration->getActivityRecordThatStartedSprint(),
            new Duration(
                $activitySprintWithDuration->getActivityRecordThatStartedSprint()->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP),
                $activityRecord->getDateTime()->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            )
        );

        // Register the completed as it is fully done now
        $sprintRegistry->add($activitySprintWithDuration);

        // Reset activity sprint to the new one -- same as in
        $reset = new ActivityRecordAndSprintReset($activityRecord);
        $activityRecordWithDuration = $reset->getActivityRecordWithDuration();
        $activitySprintWithDuration = $reset->getActivitySprintWithDuration();
    }

    // End
    $isFirstActivityRecord = false;
}

if (isset($activitySprintWithDuration)) {
    $sprintRegistry->add($activitySprintWithDuration);
}
