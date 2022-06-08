<?php

namespace VariableSign\Sms;

use VariableSign\Sms\Contracts\Driver;

class Sms
{
    protected ?array $config;

    protected ?string $gateway;

    protected ?string $driver;

    protected Builder $builder;

    protected mixed $response;

    public function __construct(array $config = null)
    {
        $this->config = $config;
        $this->builder = new Builder;
        $this->via($this->config('default'));
    }

    protected function config(?string $key = null, mixed $default = null): mixed
    {
        if ($this->config) {
            return data_get($this->config, $key, $default);
        }

        $key = $key ? 'sms.' . $key : 'sms';

        return config($key, $default);
    }

    public function via(?string $gateway = null): self
    {
        $this->gateway = $gateway;
        $this->driver = $this->config('drivers.' . $this->gateway);
        $this->builder->via($gateway);

        return $this;
    }

    public function to(array|string $recipients): self
    {
        $this->builder->to($recipients);
        
        return $this;
    }

    public function message(string $message): self
    {
        $message = trim($message);
        $this->builder->message($message);

        return $this;
    }

    public function balance(): int
    {
        $driver = $this->getDriverInstance();

        return $driver->balance();
    }

    public function send(): ?object
    {
        $driver = $this->getDriverInstance();
        $recipients = $this->builder->getRecipients();
        $message = $this->builder->getMessage(); 
        $response = $driver->send($recipients, $message);

        return collect($response);
    }

    public function report(string|int $id): ?object
    {
        $driver = $this->getDriverInstance();
        $response = $driver->report($id);

        return collect($response);
    }

    protected function getDriverInstance(): Driver
    {
        $class = $this->driver;

        return new $class($this->config('gateways.' . $this->gateway));
    }

    protected function getDriver(): ?string
    {
        return $this->driver;
    }
}
