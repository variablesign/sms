<?php

namespace VariableSign\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \VariableSign\Sms\Sms via(?string $gateway)
 * @method static \VariableSign\Sms\Sms to(array|string $recipients)
 * @method static \VariableSign\Sms\Sms message(string $message)
 * @method static int balance()
 * @method static null|array send()
 * @method static null|array report(string|int $id)
 * 
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
