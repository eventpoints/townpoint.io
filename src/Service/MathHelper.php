<?php

namespace App\Service;

use Exception;

class MathHelper
{
    public function getPercentIncrease(int $number, int $percentage) : float
    {
        return ($percentage / 100) * (float) $number;
    }
}