<?php

namespace VariableSign\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \VariableSign\Sms\Sms via(?string $gateway)
 * @method static \VariableSign\Sms\Sms to(array|string $recipients)
 * @method static \VariableSign\Sms\Sms message(string $message)
 * @method static \VariableSign\Sms\Sms dd()
 * @method static int balance()
 * @method static \Illuminate\Support\Collection send()
 * @method static \Illuminate\Support\Collection report(string|int $id)
 * @method static \Illuminate\Support\Collection otp(string $name, string|null $expiryDate, int $codeLength)
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
