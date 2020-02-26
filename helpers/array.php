<?php
if (!function_exists('array_get')) {
    function array_get($arr, $arrayIndex, $defaultValue = null)
    {
        if (is_string($arrayIndex)) {
            $arrayIndex = explode('.', $arrayIndex);
        } else {
            $arrayIndex = (array)$arrayIndex;
        }
        foreach ($arrayIndex as $index) {
            if (!isset($arr[$index])) {
                return $defaultValue;
            }
            $arr = $arr[$index];
        }
        return $arr;
    }
}
