<?php
declare(strict_types=1);

namespace ResponsibleTime\Activity\Record;

use ResponsibleTime\Activity\Record\Part\ClientMachine;
use ResponsibleTime\Activity\Record\Part\DateTime;
use ResponsibleTime\Activity\Record\Part\DesktopId;
use ResponsibleTime\Activity\Record\Part\Pid;
use ResponsibleTime\Activity\Record\Part\WindowId;
use ResponsibleTime\Activity\Record\Part\WindowTitle;
use ResponsibleTime\Activity\Record\Part\WmClass;
use RuntimeException;

final class ActivityRecord extends ActivityRecordAbstract
{
    private const PATTERN = '/^(?<datetime>\\d{4}-[0-1]\\d-[0-3]\\dT[0-2]\\d:[0-5]\\d:[0-5]\\d\\.\\d{3}Z)\\s+(?<window_id>[0]x[\\S]+)\\s+(?<desktop_id>[-+]?[0-9]+)\\s+(?<PID>[0-9]+)\\s+(?<WM_CLASS>[\\S]+.[\\S]+)\\s+(?<client_machine>[\\S]+)\\s+(?<window_title>.*)$/';
    private const PATTERN_ALTERNATIVE = '/^(?<datetime>\\d{4}-[0-1]\\d-[0-3]\\dT[0-2]\\d:[0-5]\\d:[0-5]\\d\\.\\d{3}Z)(?<window_title>.*)$/';


    public function __construct(string $recordLineFromFile, int $sourceFileLineOriginalNumber)
    {
        $this->rawRecordFromFile = $recordLineFromFile;
        $result = preg_match_all(self::PATTERN, $recordLineFromFile, $patternMatches);

        if (false === $result) {
            throw new RuntimeException(sprintf('Error matching record pattern. Impossible to extract an information out of the record #%s: %s', $sourceFileLineOriginalNumber, $recordLineFromFile));
        }

        if (0 === $result) { // Inactivity when the time is logged but the rest of the content is empty.
            $result = preg_match_all(self::PATTERN_ALTERNATIVE, $recordLineFromFile, $patternMatches);
            if (false === $result) {
                throw new RuntimeException(sprintf('Error matching record pattern. Impossible to extract an information out of the record #%s: %s', $sourceFileLineOriginalNumber, $recordLineFromFile));
            }
        }

        if (!isset($patternMatches['datetime'][0])) {
            throw new RuntimeException(sprintf('Error matching record pattern. Record #%s without datetime detected: %s', $sourceFileLineOriginalNumber, $recordLineFromFile));
        }

        if (
            false === empty($patternMatches['datetime'])
            && false === empty($patternMatches['window_id'])
            && true === empty($patternMatches['window_title'])
        ) {
            throw new RuntimeException(sprintf('Error matching record pattern. Record #%s with shifted values detected, impossible to correctly assign meanings to the parts of a record:, probably some info columns are missing %s', $sourceFileLineOriginalNumber, $recordLineFromFile));
        }

        if ( // Set of values in inactivity case
            true === empty(trim($patternMatches['window_title'][0]))
            && false === empty(trim($patternMatches['datetime'][0]))
        ) {
            $this->dateTime = new DateTime($patternMatches['datetime'][0]);
            $this->windowId = new WindowId('');
            $this->desktopId = new DesktopId('');
            $this->pid = new Pid('');
            $this->wmClass = new WmClass('');
            $this->clientMachine = new ClientMachine('');
            $this->windowTitle = new WindowTitle(
                ($patternMatches['window_id'][0] ?? '')
                . ($patternMatches['desktop_id'][0] ?? '')
                . ($patternMatches['PID'][0] ?? '')
                . ($patternMatches['WM_CLASS'][0] ?? '')
                . ($patternMatches['client_machine'][0] ?? '')
                . ($patternMatches['window_title'][0] ?? '')
            );

            return;
        }

        // Set of values in activity case
        $this->dateTime = new DateTime($patternMatches['datetime'][0]);
        $this->windowId = new WindowId($patternMatches['window_id'][0]);
        $this->desktopId = new DesktopId($patternMatches['desktop_id'][0]);
        $this->pid = new Pid($patternMatches['PID'][0]);
        $this->wmClass = new WmClass($patternMatches['WM_CLASS'][0]);
        $this->clientMachine = new ClientMachine($patternMatches['client_machine'][0]);
        $this->windowTitle = new WindowTitle($patternMatches['window_title'][0]);
    }

    public function isUserActivity(): bool
    {
        return $this->getWindowTitle() !== '';
    }
}
