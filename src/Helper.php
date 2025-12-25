<?php

use VariableSign\Sms\Sms;

if (! function_exists('sms')) {

    function sms(): Sms
    {
        return app('sms');
    }
}

if (! function_exists('sanitize_balance')) {

    function sanitize_balance(null|string|int|float $value): null|int|float
    {
        preg_match('/([\d,.]+)/', str_replace(',', '', $value ?? ''), $matches);
        $value = data_get($matches, 0);

        if ($value === null) {
            return null;
        }
        
        $parts = explode('.', $value);
        $number = data_get($parts, 0, 0);
        $decimal = data_get($parts, 1);

        return $decimal === null ? intval($number) : floatval("$number.$decimal");
    }
}