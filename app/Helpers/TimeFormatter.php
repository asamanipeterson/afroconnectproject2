<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeFormatter
{
    /**
     * Format a Carbon instance to a human-readable string with custom abbreviations.
     *
     * @param Carbon $carbonDate
     * @return string
     */
    public static function formatDiffForHumansAbbreviated(Carbon $carbonDate): string
    {
        $now = Carbon::now();
        $diff = $carbonDate->diff($now);

        // Carbon's DateInterval doesn't have a 'w' property, so we calculate it from days.
        $totalDays = $diff->days; // Get the total number of days
        $weeks = floor($totalDays / 7); // Calculate full weeks

        if ($diff->y > 0) {
            return $diff->y . 'y';
        }
        if ($diff->m > 0) {
            return $diff->m . 'mo'; // 'mo' for months to avoid conflict with minutes 'm'
        }
        // Check for weeks
        if ($weeks > 0) {
            return $weeks . 'w';
        }
        if ($diff->d > 0) {
            return $diff->d . 'd';
        }
        if ($diff->h > 0) {
            return $diff->h . 'h';
        }
        if ($diff->i > 0) {
            return $diff->i . 'm'; // 'm' for minutes
        }
        if ($diff->s > 0) {
            return $diff->s . 's';
        }

        return '0s'; // Less than a second, or just now
    }
}
