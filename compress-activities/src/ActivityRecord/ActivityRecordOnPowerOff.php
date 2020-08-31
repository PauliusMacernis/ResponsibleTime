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
use DateTimeInterface;


class ActivityRecordOnPowerOff extends ActivityRecordAbstract
{
    public function __construct(DateTimeInterface $dateTimeStart)
    {
        $this->dateTime = new DateTime($dateTimeStart->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP));
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
