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

interface ActivityRecordInterface
{
    public function getDateTime(): DateTime;
    public function getWindowId(): WindowId;
    public function getDesktopId(): DesktopId;
    public function getPid(): Pid;
    public function getWmClass(): WmClass;
    public function getClientMachine(): ClientMachine;
    public function getWindowTitle(): WindowTitle;

    public function isUserActivity(): bool;
}
