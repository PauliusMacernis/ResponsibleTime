<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

use ResponsibleTime\Main;
use ResponsibleTime\Settings;
use ResponsibleTime\Timeline\Timeline;

// Validate
$durationProvidedByUser = getopt("f:t:", ['from:', 'to:']);
if (!array_key_exists('from', $durationProvidedByUser)) {
    echo 'No datetime "from" provided. Example: --from="2020-09-16T10:38:16.424000"';
    exit;
}
if (!array_key_exists('to', $durationProvidedByUser)) {
    echo 'No datetime "to" provided. Example: --from="2020-09-16T10:38:49.290000"';
    exit;
}

// Process
$requestedUtcDateTimePeriodStart = DateTime::createFromFormat(
    'Y-m-d\TH:i:s.u',
    $durationProvidedByUser['from'],
    new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP)
);
$requestedUtcDateTimePeriodEnd = DateTime::createFromFormat(
    'Y-m-d\TH:i:s.u',
    $durationProvidedByUser['to'],
    new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP)
);

$timeline = new Timeline();
(new Main($requestedUtcDateTimePeriodStart, $requestedUtcDateTimePeriodEnd, $timeline))->processRecords();
