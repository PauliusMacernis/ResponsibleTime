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

interface ActivityRecordInterface
{
    public function getDateTime(): DateTimeInterface;

    public function getWindowId(): WindowId;

    public function getDesktopId(): DesktopId;

    public function getPid(): Pid;

    public function getWmClass(): WmClass;

    public function getClientMachine(): ClientMachine;

    public function getWindowTitle(): WindowTitle;

    /** Gets datetime of the end, this is artificial datetime value as the real one is unknown until the next record */
    public function getDateTimeEndArtificial(): DateTimeInterface;

    public function isUserActivity(): bool;

    public function __toString();
}
