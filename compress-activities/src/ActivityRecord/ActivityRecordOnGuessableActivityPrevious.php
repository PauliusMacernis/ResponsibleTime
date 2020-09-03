<?php
declare(strict_types=1);

namespace Activity\ActivityRecord;

use Activity\ActivityRecordPart\DateTime;
use Activity\Settings;
use DateTimeZone;

/**
 * The first activity record in the morning may not match the start of the day (00:00:00)
 * therefore we add the activity of power off (inactivity) or this one (activity).
 * This activity is added when the time gap between 00:00:00 and the first activity is less than MAX_ACTIVITY_RECORD_TIME_IN_SECONDS
 *
 * @see Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS
 */
class ActivityRecordOnGuessableActivityPrevious extends ActivityRecordAbstract
{
    public function __construct(ActivityRecordInterface $guessableActivityRecord)
    {
        $this->dateTime = $guessableActivityRecord->getDateTime();
        $this->windowId = $guessableActivityRecord->getWindowId();
        $this->desktopId = $guessableActivityRecord->getDesktopId();
        $this->pid = $guessableActivityRecord->getPid();
        $this->wmClass = $guessableActivityRecord->getWmClass();
        $this->clientMachine = $guessableActivityRecord->getClientMachine();
        $this->windowTitle = $guessableActivityRecord->getWindowTitle();

        $this->rawRecordFromFile = $guessableActivityRecord->getRawRecordFromFile();
    }

    public function resetDateTime(): void
    {
        $this->dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $this->getDateTime()->format('Y-m-d 00:00:00'), new DateTimeZone(Settings::RECORD_DATETIME_TIMEZONE_FOR_PHP));
    }

    public function isUserActivity(): bool
    {
        return true;
    }
}
