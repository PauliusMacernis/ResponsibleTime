<?php
declare(strict_types=1);

namespace Activity\ActivityRecord;

use Activity\ActivityRecordPart\ClientMachine;
use Activity\ActivityRecordPart\DateTime;
use Activity\ActivityRecordPart\DesktopId;
use Activity\ActivityRecordPart\Pid;
use Activity\ActivityRecordPart\WindowId;
use Activity\ActivityRecordPart\WindowTitle;
use Activity\ActivityRecordPart\WmClass;
use Activity\Settings;
use DateInterval;


class ActivityRecordOnPowerOff extends ActivityRecordAbstract
{
    public function __construct(ActivityRecord $record)
    {
        $previousRecordStart = new \DateTime($record->getDateTime());
        $previousRecordStart->add(new DateInterval('PT' . Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS . 'S'));

        $this->dateTime = new DateTime($previousRecordStart->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP));
        $this->windowId = new WindowId('');
        $this->desktopId = new DesktopId('');
        $this->pid = new Pid('');
        $this->wmClass = new WmClass('');
        $this->clientMachine = new ClientMachine('');
        $this->windowTitle = new WindowTitle(Settings::RECORD_MESSAGE_ON_POWER_OFF);
    }

    public function isUserActivity(): bool
    {
        return false;
    }
}
