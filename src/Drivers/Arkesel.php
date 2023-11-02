<?php

namespace VariableSign\Sms\Drivers;

use VariableSign\Sms\Contracts\Driver;

class Arkesel extends Driver
{
    protected function boot(): void
    {
        $this->client->withHeaders([
                'api-key' => $this->data('key')
            ]);
    }

    public function balance(): int
    {
        $response = $this->client->get($this->data('endpoints.balance'));

        if ($this->debug) {
            dd($response->object());
        }

        return (int) $response->json('data.sms_balance');
    }

    public function send(array $recipients, string $message, array $mergeData = []): ?array
    {
        $data = [
            'key' => $this->data('key'),
            'sender' => $this->data('sender'),
            'recipients' => $recipients,
            'message' => $message,
            'sandbox' => $this->data('sandbox', false)
        ];

        $response = $this->client->post($this->data('endpoints.send'), $data);

        if ($this->debug) {
            dd($response->object());
        }

        $output = null;

        foreach ($response->json('data', []) as $item) {
            $output[] = array_merge([
                'id' => $item['id'],
                'to' => $item['recipient'],
                'message' => $message,
                'status' =>  'submitted'
            ], $mergeData);
        }

        return $output;
    }

    public function report(string|int $id): ?array
    {
        $endpoint = __($this->data('endpoints.report'), [
            'id' => $id
        ]);

        $data = [
            'key' => $this->data('key')
        ];

        $response = $this->client->get($endpoint, $data);

        if ($this->debug) {
            dd($response->object());
        }

        $output = null;

        $output[] = [
            'id' => $id,
            'to' => $response->json('data.recipient'),
            'status' =>  strtolower($response->json('data.status'))
        ];

        return $output;
    }
}