<?php

namespace CodexSoft\TimeService;

use Carbon\Carbon;

/**
 * Class NormalClockService
 * Default implementation, return real server date/time in default timezone (DTZ).
 */
class NormalTimeService implements TimeServiceInterface
{

    /**
     * @return Carbon|\DateTime
     */
    public function now(): Carbon
    {
        return Carbon::now(new \DateTimeZone('UTC'));
    }
}
