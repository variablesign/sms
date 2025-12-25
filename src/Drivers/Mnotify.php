<?php

namespace VariableSign\Sms\Drivers;

use VariableSign\Sms\Contracts\Driver;

class Mnotify extends Driver
{
    protected function boot(): void
    {
        //
    }

    public function balance(): null|int|float
    {
        $data = [
            'key' => $this->data('key')
        ];

        $response = $this->client->get($this->data('endpoints.balance') , $data);

        if ($this->debug) {
            dd($response->object());
        }

        return sanitize_balance($response->json('balance'));
    }

    public function send(array $recipients, string $message, array $mergeData = []): ?array
    {
        $data = [
            'key' => $this->data('key'),
            'sender' => $this->data('sender'),
            'recipient' => $recipients,
            'message' => $message
        ];

        $response = $this->client->post($this->data('endpoints.send'), $data);

        if ($this->debug) {
            dd($response->object());
        }

        $id = $response->json('summary._id');
        $output = null;

        foreach ($response->json('summary.numbers_sent', []) as $number) {
            $output[] = array_merge([
                'id' => $id,
                'to' => $number,
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

        foreach ($response->json('report', []) as $item) {
            $output[] = [
                'id' => $id,
                'to' => $item['recipient'],
                'status' =>  strtolower($item['status'])
            ];
        }

        return $output;
    }
}