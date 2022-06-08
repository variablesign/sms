<?php

namespace VariableSign\Sms\Contracts;

use Illuminate\Support\Facades\Http;

abstract class Driver
{
    protected array $data = [];

    protected object $client;

    protected int $timeout = 10;

    protected bool $verify = true;

    protected string $contentType = 'application/json';

    protected bool $debug;

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->client = Http::timeout($this->data('timeout', $this->timeout))
            ->accept($this->contentType)
            ->connectTimeout($this->data('timeout', $this->timeout))
            ->when(!$this->data('verify', $this->verify), function ($http) {
                return $http->withoutVerifying();
            });

        $this->boot();
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }

    public function dd(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    protected function boot(): void
    {
        //
    }

    abstract public function balance(): int;

    abstract public function send(array $recipients, string $message, array $mergeData = []): ?array;

    abstract public function report(string|int $id): ?array;
}