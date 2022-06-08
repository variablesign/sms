<?php

if (! function_exists('sms')) {
    /**
     * Access Sms class through helper.
     * @return \VariableSign\Sms\Sms
     */
    function sms()
    {
        return app('sms');
    }
}