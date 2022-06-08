<?php

namespace VariableSign\Sms\Drivers;

use VariableSign\Sms\Contracts\Driver;

class Mnotify extends Driver
{
    protected function boot(): void
    {
        //
    }

    public function balance(): int
    {
        return 0;
    }

    public function send(array $recipients, string $message, array $mergeData = []): ?array
    {
        return null;
    }

    public function report(string|int $id): ?array
    {
        return null;
    }
}