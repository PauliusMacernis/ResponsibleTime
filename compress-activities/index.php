<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

use Activity\ActivityRecordConversionToActivitySprint;
use Activity\ActivitySprintWithDurationRegistry\ActivitySprintWithDurationRegistry;
use Activity\Records\Records;

$fileToRead = '/home/paulius/.responsible-time/activities/2020-08-28 (copy).txt';
//$fileToRead = '/home/paulius/.responsible-time/activities/2020-08-28-min.txt';
$fileToWrite = $fileToRead . '.unique';

$records = new Records($fileToRead);
$sprintRegistry = new ActivitySprintWithDurationRegistry();

$isFirstActivityRecord = true;

$activitySprintWithDuration = null;
foreach ($records as $activityRecord) {
    $activitySprintWithDuration = (new ActivityRecordConversionToActivitySprint($sprintRegistry, $activityRecord, $activitySprintWithDuration))->getActivitySprintWithDuration();
}

if (null !== $activitySprintWithDuration) {
    $sprintRegistry->add($activitySprintWithDuration);
}
