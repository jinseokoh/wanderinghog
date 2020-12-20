<?php

namespace App\Support;

class AgeCalculator
{
    public function getDobFromAge(int $age)
    {
        return date('Y-m-d', strtotime($age . ' years ago'));
    }

    public function getYearRange(int $minAge, int $maxAge): array
    {
        $maxYear = $this->getBirthYear($minAge);
        $minYear = $this->getBirthYear($maxAge);

        return range($minYear - 2, $maxYear + 1);
    }

    public function getBirthYear(int $age): int
    {
        return (int) date('Y', strtotime("{$age} years ago"));
    }
}
