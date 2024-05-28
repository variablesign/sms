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

    public function balance(): null|int
    {
        $response = $this->client->get($this->data('endpoints.balance'));

        if ($this->debug) {
            dd($response->object());
        }
        
        return filter_var($response->json('data.remaining_balance'), FILTER_SANITIZE_NUMBER_INT);
    }

    public function send(array $recipients, string $message, array $mergeData = []): ?array
    {
        $output = null;

        foreach ($recipients as $number) {
            $response = $this->client->post($this->data('endpoints.send'), [
                    'sender_id' => $this->data('sender'),
                    'recipient' => $number,
                    'message' => $message
                ]);

            if ($this->debug) {
                dump($response->object());
            }

            if ($response->json('status') == 'success') {
                $output[] = array_merge([
                    'id' => $response->json('data.uid'),
                    'to' => $response->json('data.to'),
                    'message' => $message,
                    'status' =>  strtolower($response->json('data.status'))
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

        if ($response->json('data', null)) {
            $output[] = [
                'id' => $id,
                'to' => $response->json('data.to'),
                'status' =>  strtolower($response->json('data.status'))
            ];
        }

        return $output;
    }
}