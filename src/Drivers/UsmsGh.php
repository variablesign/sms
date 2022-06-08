<?php

namespace VariableSign\Sms\Drivers;

use VariableSign\Sms\Contracts\Driver;

class UsmsGh extends Driver
{
    protected function boot(): void
    {
        $this->client->withHeaders([
                'Authorization' => 'Bearer ' . $this->data('key')
            ]);
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