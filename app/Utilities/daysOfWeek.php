<?php
namespace App\Utilities;

class DaysOfWeek
{
    /**
     * Returns array consisting of the days of the week
     *
     * @return array
     */
    public static function retrieveDaysOfWeek(): array
    {
        return ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    }
}
