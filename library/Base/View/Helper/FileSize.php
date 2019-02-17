<?php
/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-06-17
 * Time: 09:06
 */

class Base_View_Helper_FileSize
{
    /**
     * @param $bytes
     * @param string $unit
     * @param int $decimals
     * @return string
     */
    public function fileSize($bytes, $unit = "", $decimals = 2)
    {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4,
            'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        $value = 0;
        if ($bytes > 0)
        {
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes)/log(1024));
                $unit = array_search($pow, $units);
            }

            $value = ($bytes/pow(1024,floor($units[$unit])));
        }

        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        return sprintf('%.' . $decimals . 'f '.$unit, $value);
    }
} 