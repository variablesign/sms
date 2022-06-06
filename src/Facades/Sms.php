<?php

namespace VariableSign\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VariableSign\Sms\Sms
 */
class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}
