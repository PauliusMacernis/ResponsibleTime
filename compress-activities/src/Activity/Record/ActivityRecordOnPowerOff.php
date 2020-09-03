<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Record;

use DateTimeInterface;
use ResponsibleTime\Activity\Record\Part\ClientMachine;
use ResponsibleTime\Activity\Record\Part\DesktopId;
use ResponsibleTime\Activity\Record\Part\Pid;
use ResponsibleTime\Activity\Record\Part\WindowId;
use ResponsibleTime\Activity\Record\Part\WindowTitle;
use ResponsibleTime\Activity\Record\Part\WmClass;
use ResponsibleTime\Settings;

class ActivityRecordOnPowerOff extends ActivityRecordAbstract
{
    public function __construct(DateTimeInterface $dateTimeStart)
    {
        $this->dateTime = $dateTimeStart;
        $this->windowId = new WindowId('');
        $this->desktopId = new DesktopId('');
        $this->pid = new Pid('');
        $this->wmClass = new WmClass(Settings::POWER_OFF_ACTIVITY_RECORD_WM_CLASS);
        $this->clientMachine = new ClientMachine('');
        $this->windowTitle = new WindowTitle(Settings::POWER_OFF_ACTIVITY_RECORD_WINDOW_TITLE);

        $this->rawRecordFromFile =
            $dateTimeStart->format(Settings::RECORD_DATETIME_FORMAT_FOR_PHP)
            . ' '
            . Settings::POWER_OFF_ACTIVITY_RECORD_WM_CLASS
            . ' '
            . Settings::POWER_OFF_ACTIVITY_RECORD_WINDOW_TITLE;
    }

    public function isUserActivity(): bool
    {
        return false;
    }
}
