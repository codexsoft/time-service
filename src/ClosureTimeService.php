<?php

namespace CodexSoft\TimeService;

use Carbon\Carbon;

/**
 * Class ClosureClockService
 * Implementation of ClockService via custom Closure
 */
class ClosureTimeService implements TimeServiceInterface
{

    /** @var \Closure */
    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @return Carbon|\DateTime
     */
    public function now(): Carbon
    {
        $closure = $this->closure;
        $dateTime = $closure();
        if (!$dateTime instanceof Carbon) {
            if (!$dateTime instanceof \DateTime) {
                throw new \RuntimeException('ClosureClockService.closure must return \DateTime or Carbon');
            }
            Carbon::instance($dateTime);
        }
        return $dateTime;
    }
}
