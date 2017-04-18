<?php

namespace app\components;

/**
 * Helper
 * @package app\components
 */
class Helper
{
    /**
     * @param float $hours
     * @return string
     */
    static public function formatHours($hours)
    {
        return floor($hours) . ':' . sprintf("%02d", floor(($hours * 60) % 60));
    }
}