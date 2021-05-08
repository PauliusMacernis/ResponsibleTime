<?php
declare(strict_types=1);

namespace ResponsibleTime;

use DateTimeInterface;
use LogicException;
use ResponsibleTime\Activity\Files\Files;
use ResponsibleTime\Activity\Record\ActivityRecordInterface;
use ResponsibleTime\Activity\Record\ActivityRecordOnPowerOff;
use ResponsibleTime\Activity\Records\Records;
use ResponsibleTime\Debug\Debug;
use ResponsibleTime\Duration\Duration;
use ResponsibleTime\Timeline\Timeline;
use ResponsibleTime\UserDateTime\UserDateTimeValidation;
use RuntimeException;

class Main
{
    private DateTimeInterface $requestedUtcDateTimePeriodStart;
    private DateTimeInterface $requestedUtcDateTimePeriodEnd;
    private Timeline $timeline;
    private int $firstRecordCaseForDebugging;

    public function __construct(DateTimeInterface $requestedUtcDateTimePeriodStart, DateTimeInterface $requestedUtcDateTimePeriodEnd, Timeline $timeline)
    {
        (new UserDateTimeValidation())->validate($requestedUtcDateTimePeriodStart, $requestedUtcDateTimePeriodEnd);

        $this->requestedUtcDateTimePeriodStart = $requestedUtcDateTimePeriodStart;
        $this->requestedUtcDateTimePeriodEnd = $requestedUtcDateTimePeriodEnd;
        $this->timeline = $timeline;
    }


    public function processRecords(): void
    {
        Debug::echoUserDatetimeChoice($this->requestedUtcDateTimePeriodStart, $this->requestedUtcDateTimePeriodEnd);

        $filesToReadForRecords = new Files($this->requestedUtcDateTimePeriodStart, $this->requestedUtcDateTimePeriodEnd);

        Debug::echoFilesToReadForRecords($filesToReadForRecords);

        $isFirstRecord = true;
        $previousActivity = null;
        foreach ($filesToReadForRecords as $fileToRead) {
            $records = new Records($fileToRead);
            foreach ($records as $currentActivityThatMayCountIn) {

                // The main issue with this cycle is:
                // The end datetime of activity may be needed to be adjusted (
                //  from time - because activity started before the user's requested datetime but ended after, etc.;
                //   to time - if the computer has been turned off, another activity record came in earlier/later or no activity after that at all)
                // Which means we know the real from-to datetimes of activity record at the point of the next analysed item only,
                //   e.g. on a second record analysis
                // Therefore there are few types of activity records here:
                //  1. Very first activity record - with artificial end datetime - because no data on the next activity record start datetime yet
                //  2. Previous activity record - with known start and end datetime - because it's clear when it starts and ends
                //  3. Last activity record - same nature as of 1. However, we know it is the last item AFTER exiting this cycle only.

                if ($this->isRecordTooEarly($currentActivityThatMayCountIn)) {
                    // Skip activities ending before OR at the requested datetime [from]
                    continue;
                }

                if ($this->isRecordTooLate($currentActivityThatMayCountIn)) {
                    // Skip all activities starting after OR at the requested datetime [to]
                    break 2;
                }

                // Here we are left with:
                // Common case:
                // - activity block that started and ended within the requested range [to-from]

                // Edge cases:
                // - activity block that started earlier but ended within the requested range [to-from]
                // - activity block that started within the requested range but ended off the requested range end datetime

                // Anomaly cases:
                // - activity block that started before or at the requested FROM and ended after or at the requested TO.
                // ???

                if ($isFirstRecord) {
                    $this->processRecordFirst($currentActivityThatMayCountIn);
                    $previousActivity = clone $this->timeline->getItemPreliminary()->getActivityRecord();
                    $isFirstRecord = false;

                    continue;
                }

                $this->processRecordSecondAndLater($currentActivityThatMayCountIn);
                $previousActivity = clone $this->timeline->getItemPreliminary()->getActivityRecord();
            }
        }

        if (!isset($previousActivity)) {
            $this->processRecordNone();

            return;
        }

        $this->processRecordLast($previousActivity);
    }

    /**
     * Process the very first record, add inactivity before if necessary.
     */
    private function processRecordFirst(ActivityRecordInterface $activityRecord): void
    {
        if ( // 3
            $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 3;
            // Change start date to "user's from", take it in
            $this->timeline->addItemPreliminary($activityRecord, $this->requestedUtcDateTimePeriodStart, $activityRecord->getDateTimeEndArtificial());
        } elseif ( // 4
            $activityRecord->getDateTime() == $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 4;
            // Take it in as it is
            $this->timeline->addItemPreliminary($activityRecord, $activityRecord->getDateTime(), $activityRecord->getDateTimeEndArtificial());
        } elseif ( // 5
            $activityRecord->getDateTime() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 5;
            $this->timeline->addItemPreliminary(
                new ActivityRecordOnPowerOff($this->requestedUtcDateTimePeriodStart),
                $this->requestedUtcDateTimePeriodStart,
                $activityRecord->getDateTime()
            );
            $this->timeline->savePreliminaryItemToTimeline();
            // Take it in as it is
            $this->timeline->addItemPreliminary($activityRecord, $activityRecord->getDateTime(), $activityRecord->getDateTimeEndArtificial());
        } elseif ( // 6
            $activityRecord->getDateTime() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() == $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 6;
            $this->timeline->addItemPreliminary(
                new ActivityRecordOnPowerOff($this->requestedUtcDateTimePeriodStart),
                $this->requestedUtcDateTimePeriodStart,
                $activityRecord->getDateTime()
            );
            $this->timeline->savePreliminaryItemToTimeline();
            // Take it in as it is
            $this->timeline->addItemPreliminary($activityRecord, $activityRecord->getDateTime(), $this->requestedUtcDateTimePeriodEnd);
        } elseif ( // 7
            $activityRecord->getDateTime() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 7;
            $this->timeline->addItemPreliminary(
                new ActivityRecordOnPowerOff($this->requestedUtcDateTimePeriodStart),
                $this->requestedUtcDateTimePeriodStart,
                $activityRecord->getDateTime()
            );
            $this->timeline->savePreliminaryItemToTimeline();
            // Cut end date to "user's end"
            $this->timeline->addItemPreliminary($activityRecord, $activityRecord->getDateTime(), $this->requestedUtcDateTimePeriodEnd);
        }
//        elseif ( // 8
//
//            // @TODO: Do we need items [8,9] at all??? Makes no sense to me... E.g. we do not set any preliminary value in 8 and 9
//
//            $activityRecord->getDateTime() > $this->requestedUtcDateTimePeriodStart
//            && $activityRecord->getDateTime() == $this->requestedUtcDateTimePeriodEnd
//            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
//            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodEnd
//        ) {
//            $this->firstRecordCase = 8;
//
//            $this->timeline->addItemPreliminary(
//                new ActivityRecordOnPowerOff($this->requestedUtcDateTimePeriodStart),
//                $this->requestedUtcDateTimePeriodStart,
//                $this->requestedUtcDateTimePeriodEnd
//            );
//            $this->timeline->savePreliminaryItemToTimeline();
//            // Drop it
//            return;
//        } elseif ( // 9
//            $activityRecord->getDateTime() > $this->requestedUtcDateTimePeriodStart
//            && $activityRecord->getDateTime() > $this->requestedUtcDateTimePeriodEnd
//            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
//            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodEnd
//        ) {
//            $this->firstRecordCase = 9;
//            $this->timeline->addItemPreliminary(
//                new ActivityRecordOnPowerOff($this->requestedUtcDateTimePeriodStart),
//                $this->requestedUtcDateTimePeriodStart,
//                $this->requestedUtcDateTimePeriodEnd
//            );
//            $this->timeline->savePreliminaryItemToTimeline();
//            // Drop it
//            return;
//        }
        elseif ( // 10
            $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 10;
            // Cut it to fit [user's from - user's to]
            $this->timeline->addItemPreliminary($activityRecord, $this->requestedUtcDateTimePeriodStart, $this->requestedUtcDateTimePeriodEnd);
        } elseif ( // 11
            $activityRecord->getDateTime() == $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() > $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() == $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 11;
            // Take it in as it is (same as "Cut it to fit [user's from - user's to]")
            $this->timeline->addItemPreliminary($activityRecord, $this->requestedUtcDateTimePeriodStart, $this->requestedUtcDateTimePeriodEnd);
        } elseif ( // 12, 13
            $activityRecord->getDateTime() == $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() == $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() == $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() == $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 12;
            // Take it in as it is (same as drop it) because it is 0-length activity ant tells not much.
            $this->timeline->addItemPreliminary($activityRecord, $this->requestedUtcDateTimePeriodStart, $this->requestedUtcDateTimePeriodEnd);
            //$this->registerInactivity($this->requestedUtcDateTimePeriodStart, $this->requestedUtcDateTimePeriodEnd);
        } else {
            throw new RuntimeException('Unpredicted scenario. Code error. Bug.');
        }
    }

    /**
     * Process the activity record with known time frame up to the next activity.
     */
    private function processRecordSecondAndLater(ActivityRecordInterface $activityRecordNext): void
    {
        if (false === $this->timeline->isSetItemPreliminary()) {
            throw new LogicException('Preliminary item should be set up to this point. If it is not (and so you get this message) then it is a bug.');
        }

        // Inject inactivity?
        $preliminarPrevRecordDuration = new Duration($this->timeline->getItemPreliminary()->getFrom(), $activityRecordNext->getDateTime());
        if ($preliminarPrevRecordDuration->getDurationInSeconds() > Settings::MAX_ACTIVITY_RECORD_TIME_IN_SECONDS) {
            // Inject inactivity
            // Yes:
            //
            // Prev record should be modified to:
            // START: ok.
            // END: prev record start + MAX_ALLOWED_RECORD_DURATION
            $newEndDateTimeOfPrevRecord = $this->timeline->getItemPreliminary()->getActivityRecord()->getDateTimeEndArtificialByCustomDateTimeFrom($this->timeline->getItemPreliminary()->getFrom());
            $this->timeline->addItemPreliminary(
                $this->timeline->getItemPreliminary()->getActivityRecord(),
                $this->timeline->getItemPreliminary()->getFrom(),
                $newEndDateTimeOfPrevRecord
            );
            // > Save such inactivity to a "blockchain"
            $this->timeline->savePreliminaryItemToTimeline();
            // Inactivity is
            // START: prev record start + MAX_ALLOWED_RECORD_DURATION
            // END: next record start
            // > INACTIVITY BECOMES prev activity (assign it).
            $this->timeline->addItemPreliminary(
                new ActivityRecordOnPowerOff($newEndDateTimeOfPrevRecord),
                $newEndDateTimeOfPrevRecord,
                $activityRecordNext->getDateTime()
            );
            $this->timeline->savePreliminaryItemToTimeline();
            //
            // Next record should be modified to:
            // START: ok
            // END: ok
            $this->timeline->addItemPreliminary(
                $activityRecordNext,
                $activityRecordNext->getDateTime(),
                $activityRecordNext->getDateTimeEndArtificial(),
            );

            return;
        }

        // Change prev record datetime end to match next record start?
        // Only "valid items" should pass up to here therefore we should always modify
        //  the end datetime of the previous record to be the same as the start datetime of the next record
        // In case the next activity is too long or otherwise not fully acceptable, it's end will be modified later on.
//        if($this->timeline->getItemPreliminary()->getFrom() !== $activityRecordNext->getDateTime()) {

        $this->timeline->addItemPreliminary(
            $this->timeline->getItemPreliminary()->getActivityRecord(),
            $this->timeline->getItemPreliminary()->getFrom(),
            $activityRecordNext->getDateTime()
        );

        $this->timeline->savePreliminaryItemToTimeline();

        $this->timeline->addItemPreliminary(
            $activityRecordNext,
            $activityRecordNext->getDateTime(),
            $activityRecordNext->getDateTimeEndArtificial(),
        );
    }

    /**
     * Process "no activity records" case, e.g. of the times when data has not been collected in the past, the far future, etc.
     */
    private function processRecordNone(): void
    {
        echo "None\n";
    }

    /**
     * Process the very last record known, add inactivity if applies.
     */
    private function processRecordLast(ActivityRecordInterface $activity): void
    {
        Debug::echoMethodName(__METHOD__);

        if ($activity->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodEnd) {
            $this->timeline->addItemPreliminary(
                $activity,
                $activity->getDateTime(),
                $activity->getDateTimeEndArtificial()
            );
            $this->timeline->savePreliminaryItemToTimeline();
            $this->timeline->addItemPreliminary(
                new ActivityRecordOnPowerOff($activity->getDateTimeEndArtificial()),
                $activity->getDateTimeEndArtificial(),
                $this->requestedUtcDateTimePeriodEnd
            );
            $this->timeline->savePreliminaryItemToTimeline();

            return;
        }

        $this->timeline->addItemPreliminary(
            $activity,
            $activity->getDateTime(),
            $this->requestedUtcDateTimePeriodEnd
        );
        $this->timeline->savePreliminaryItemToTimeline();
    }

    private function isRecordTooEarly(ActivityRecordInterface $activityRecord): bool
    {
        if ($activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodStart) {
            return true;
        }

        if ( // 1
            $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 1;
            // drop
            return true;
        }

        if ( // 2
            $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTime() < $this->requestedUtcDateTimePeriodEnd
            && $activityRecord->getDateTimeEndArtificial() == $this->requestedUtcDateTimePeriodStart
            && $activityRecord->getDateTimeEndArtificial() < $this->requestedUtcDateTimePeriodEnd
        ) {
            $this->firstRecordCaseForDebugging = 2;
            // drop
            return true;
        }

        return false;
    }

    private function isRecordTooLate(ActivityRecordInterface $currentActivityThatMayCountIn): bool
    {
        return $currentActivityThatMayCountIn->getDateTime() >= $this->requestedUtcDateTimePeriodEnd;
    }
}
