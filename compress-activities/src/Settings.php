<?php
declare(strict_types=1);

namespace Activity;

class Settings
{
    /**
     * @var int
     * We expect activity records to appear every second.
     *
     * However, sometimes the computer goes down due to known or unknown reason (e.g. power outage)
     * then the next activity will be recorded after some time.
     *
     * @TODO:
     * This setting tells how long we "wait" for another activity record to appear before we treat the activity suspended (inactivity started).
     *
     * For example, if the first record was at 00:00:05 and the next one is at 08:00:05
     * then we treat that the first activity lasted for the amount of time the setting says
     * and then we treat the activity to be down (inactivity started) due to unknown reason (e.g. computer turned off and we went to sleep)
     *
     * Notice: Making the interval longer than the actual activity record will not make that activity record longer.
     * For example, if the activity record lasted for 1 second (default) and we say 60 seconds in the setting
     * then the activity record will be treated as 1 second long anyway.
     */
    public const MAX_ACTIVITY_RECORD_TIME_IN_SECONDS = 2;

    /**
     * @var string
     * @See: Timezone settings in collect-activities/collect-activities.sh  - must be the same
     * @TODO: DRY
     *
     * Defines time format of the record in the initial text-log file.
     */
    public const RECORD_DATETIME_FORMAT_FOR_PHP = "Y-m-d\TH:i:s.v\Z";
    public const RECORD_DATETIME_TIMEZONE_FOR_PHP = "Z";
    /**
     * @var string These are the files in where records are stored. The format matches the ones in PHP datetime.
     * @TODO: DRY: See shell scripts collecting records to this file
     */
    public const FILENAME_DATETIME_FORMAT_FOR_RECORDS = "Y-m-d";

    /** Meanings assigned to the detected inactivity/power off activity */
    public const POWER_OFF_ACTIVITY_RECORD_WM_CLASS = "INACTIVITY";
    public const POWER_OFF_ACTIVITY_RECORD_WINDOW_TITLE = "POWER IS OFF";
}
