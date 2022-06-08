<?php

namespace VariableSign\Sms\Drivers;

use VariableSign\Sms\Contracts\Driver;

class SmsOnlineGh extends Driver
{
    protected function boot(): void
    {
        $host = parse_url($this->data('endpoints.send'), 1);
        $this->client->withHeaders([
                'Host' => $host,
                'Authorization' => 'key ' . $this->data('key')
            ]);
    }

    public function balance(): int
    {
        $response = $this->client->post($this->data('endpoints.balance'));

        if ($this->debug) {
            dd($response->object());
        }

        return $response->json('data.balance.amount', 0);
    }

    public function send(array $recipients, string $message, array $mergeData = []): ?array
    {
        $destinations = collect($recipients)
            ->transform(function ($item, $key) {
                return [
                    'to' => $item
                ];
            })
            ->all();

        $data = [
            'messages' => [
                [
                    'text' => $message,
                    'type' => 0,
                    'sender' => $this->data('sender'),
                    'destinations' => $destinations
                ]
            ]
        ];
        
        $response = $this->client->post($this->data('endpoints.send'), $data);

        if ($this->debug) {
            dd($response->object());
        }

        $id = $response->json('data.messages.0.reference');
        $responseCodes = ['2104', '2105', '2107', '2108', '2109'];
        $output = null;

        foreach ($response->json('data.messages.0.destinations', []) as $value) {
            $output[] = array_merge([
                'id' => $id,
                'to' => $value['to'],
                'message' => $message,
                'status' =>  in_array($value['status']['id'], $responseCodes) ? 'submitted' : null
            ], $mergeData);
        }

        return $output;
    }

    public function report(string|int $id): ?array
    {
        $response = $this->client->post($this->data('endpoints.report'), [
                'reference' => $id
            ]);

        if ($this->debug) {
            dd($response->object());
        }

        $output = null;

        foreach ($response->json('data.messages.0.destinations', []) as $value) {
            $output[] = [
                'id' => $id,
                'to' => $value['to'],
                'status' => $value['status']['id'] == '2110' ? 'delivered' : null
            ];
        }

        return $output;
    }
}