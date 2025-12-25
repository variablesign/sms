<?php

namespace VariableSign\Sms\Drivers;

use VariableSign\Sms\Contracts\Driver;

class TxtConnect extends Driver
{
    protected function boot(): void
    {
        $this->client->withHeaders([
                'Authorization' => 'Bearer ' . $this->data('key')
            ]);
    }

    public function balance(): null|int|float
    {
        $response = $this->client->get($this->data('endpoints.balance'));

        if ($this->debug) {
            dd($response->object());
        }

        return sanitize_balance($response->json('sms'));
    }

    public function send(array $recipients, string $message, array $mergeData = []): ?array
    {
        $output = null;

        foreach ($recipients as $number) {
            $response = $this->client->post($this->data('endpoints.send'), [
                    'from' => $this->data('sender'),
                    'to' => $number,
                    'sms' => $message,
                    'unicode' => intval($this->data('unicode'))
                ]);

            if ($this->debug) {
                dump($response->object());
            }

            if ($response->json('msg') == 'Sms send Sucessfull') {
                $output[] = array_merge([
                    'id' => $response->json('messageId'),
                    'to' => $number,
                    'message' => $message,
                    'status' =>  $response->json('msg')
                ], $mergeData);
            }
        }

        if ($this->debug) {
            dd($response->object());
        }

        return $output;
    }

    public function report(string|int $id): ?array
    {
        $endpoint = __($this->data('endpoints.report'), [
            'id' => $id
        ]);

        $response = $this->client->get($endpoint);
        $output = null;

        if ($this->debug) {
            dd($response->object());
        }

        if ($response->json('msg', null)) {
            $output[] = [
                'id' => $id,
                'status' =>  $response->json('msg')
            ];
        }

        return $output;
    }
}