<?php

namespace CodexSoft\TimeService;

use Carbon\Carbon;

/**
 * Sometimes we need make time to speed up or to speed down: for example, 60 second per real second.
 * It can be useful for tests and for simulation purposes.
 *
 * There are some libraries for same purposes. But they use PHP non-standard extentions
 * https://github.com/hnw/php-timecop
 * https://github.com/rezzza/TimeTraveler
 * https://softwareengineering.stackexchange.com/questions/235145/real-time-unit-testing-or-how-to-mock-now
 * https://github.com/wolfcw/libfaketime
 *
 * Carbon also has ability to set faked DateTime
 * Carbon::setTestNow(); // http://carbon.nesbot.com/docs/#api-testing
 */
class ScaledTimeService implements TimeServiceInterface
{

    /** @var int timestamp */
    private $startedAtTimestamp;

    /** @var float timestamp */
    private $startedAtTimestampMicro;

    /** @var int */
    private $clockMultiplicator = 1;

    /**
     * FakeClockService constructor.
     *
     * @param int $multiplicator how much faster a timer should go? x2? x10?
     * @param int|null $startTimestamp a moment from which start time scaling
     */
    public function __construct(int $multiplicator = 1, int $startTimestamp = null)
    {
        $this->clockMultiplicator = $multiplicator;
        $this->startedAtTimestamp = $startTimestamp ?? time();
        $this->startedAtTimestampMicro = $startTimestamp ?? microtime(true);
    }

    public function realSecondsElapsed()
    {
        return time() - $this->startedAtTimestamp;
    }

    public function realMicroSecondsElapsed()
    {
        return microtime(true) - $this->startedAtTimestampMicro;
    }

    public function secondsElapsed()
    {
        return round($this->realMicroSecondsElapsed() * $this->clockMultiplicator);
    }

    public function now(): Carbon
    {
        // if clock not scaled, just returning current real server time
        if ($this->clockMultiplicator === 1) {
            return Carbon::now(); // yes, Carbon::now() here!
        }

        // otherwise, calculating virtual scaled time
        return Carbon::createFromTimestampUTC($this->startedAtTimestamp + $this->secondsElapsed());
    }

}
