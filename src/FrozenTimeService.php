<?php

namespace CodexSoft\TimeService;

use Carbon\Carbon;

/**
 * Class FrozenClockService
 * In some cases we need to freeze a date & time
 * For example, every iterating of loop we add 10 seconds to virtual timer.
 * No dependency of real server clock.
 * we need to keep timezone
 */
class FrozenTimeService implements TimeServiceInterface
{

    /** @var Carbon */
    private $frozenDateTime;

    /** @var string */
    private $targetTimezone;

    public function __construct(\DateTime $frozenDateTime = null, $targetTimezone = 'UTC')
    {
        $this->targetTimezone = $targetTimezone;
        $this->setFrozenDateTime($frozenDateTime ?? Carbon::now()); // yes, Carbon::now() here!
    }


    public function now(): Carbon
    {
        return $this->frozenDateTime->copy();
    }

    /**
     * @return Carbon|\DateTime
     */
    public function modifyCurrentDateTime(): Carbon
    {
        return $this->frozenDateTime;
    }

    /**
     * @param Carbon|\DateTime $frozenDateTime
     *
     * @return FrozenTimeService
     */
    public function setFrozenDateTime(\DateTime $frozenDateTime): FrozenTimeService
    {
        if (!$frozenDateTime instanceof Carbon) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $frozenDateTime = Carbon::instance($frozenDateTime);
        }
        $this->frozenDateTime = $frozenDateTime;
        $this->frozenDateTime->setTimezone($this->targetTimezone); // keeping target timezone
        return $this;
    }

}
