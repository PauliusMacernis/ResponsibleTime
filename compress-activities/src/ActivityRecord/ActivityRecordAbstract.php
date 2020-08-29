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

abstract class ActivityRecordAbstract implements ActivityRecordInterface
{
    /** @var DateTime */
    protected $dateTime;

    /** @var WindowId */
    protected $windowId;

    /** @var DesktopId */
    protected $desktopId;

    /** @var Pid */
    protected $pid;

    /** @var WmClass */
    protected $wmClass;

    /** @var ClientMachine */
    protected $clientMachine;

    /** @var WindowTitle */
    protected $windowTitle;


    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getWindowId(): WindowId
    {
        return $this->windowId;
    }

    public function getDesktopId(): DesktopId
    {
        return $this->desktopId;
    }

    public function getPid(): Pid
    {
        return $this->pid;
    }

    public function getWmClass(): WmClass
    {
        return $this->wmClass;
    }

    public function getClientMachine(): ClientMachine
    {
        return $this->clientMachine;
    }

    public function getWindowTitle(): WindowTitle
    {
        return $this->windowTitle;
    }

    abstract public function isUserActivity(): bool;
}