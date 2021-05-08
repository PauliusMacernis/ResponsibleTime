<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Record;

use DateInterval;
use DateTimeInterface;
use ResponsibleTime\Activity\Record\Part\ClientMachine;
use ResponsibleTime\Activity\Record\Part\DesktopId;
use ResponsibleTime\Activity\Record\Part\Pid;
use ResponsibleTime\Activity\Record\Part\WindowId;
use ResponsibleTime\Activity\Record\Part\WindowTitle;
use ResponsibleTime\Activity\Record\Part\WmClass;
use ResponsibleTime\Settings;
use RuntimeException;

abstract class ActivityRecordAbstract implements ActivityRecordInterface
{
    /** @var string */
    protected $rawRecordFromFile;

    /** @var DateTimeInterface */
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


    public function getRawRecordFromFile(): string
    {
        return $this->rawRecordFromFile;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTimeInterface $dateTime): void
    {
        $this->dateTime = $dateTime;
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

    public function getDateTimeEndArtificial(): DateTimeInterface
    {
        $dateTimeEndArtificial = $this->getDateTimeEndArtificialByCustomDateTimeFrom($this->dateTime);

        if ($this->dateTime > $dateTimeEndArtificial) {
            throw new RuntimeException('Activity record "from" datetime is later than activity record "to" datetime. Such scenario is not allowed.');
        }

        return $dateTimeEndArtificial;
    }

    public function getDateTimeEndArtificialByCustomDateTimeFrom(DateTimeInterface $dateTimeFrom): DateTimeInterface
    {
        $dateTimeFromCloned = clone $dateTimeFrom; // We need to clone, because we don't want to change the original value with "add" later on.

        $dateTimeFromCloned->add(new DateInterval(sprintf('PT%sS', Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS)));

        return $dateTimeFromCloned;
    }

    public function __toString()
    {
        return $this->rawRecordFromFile;
    }

    abstract public function isUserActivity(): bool;

}
