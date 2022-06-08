<?php

namespace VariableSign\Sms;

use VariableSign\Sms\Contracts\Driver;
use Illuminate\Support\Collection;

class Sms
{
    protected ?array $config;

    protected ?string $gateway;

    protected ?string $driver;

    protected Builder $builder;

    protected mixed $response;

    protected bool $debug = false;

    public function __construct(array $config = null)
    {
        $this->config = $config;
        $this->builder = new Builder;
        $this->via();
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
        $this->gateway = $gateway ?? $this->config('default');
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

    public function dd(): self
    {
        $this->debug = true;

        return $this;
    }

    public function balance(): int
    {
        $driver = $this->getDriverInstance();

        return $driver->dd($this->debug)->balance();
    }

    public function send(): Collection
    {
        $driver = $this->getDriverInstance();
        $recipients = $this->builder->getRecipients();
        $message = $this->builder->getMessage(); 
        $response = $driver->dd($this->debug)->send($recipients, $message);

        return collect($response);
    }

    public function report(string|int $id): Collection
    {
        $driver = $this->getDriverInstance();
        $response = $driver->dd($this->debug)->report($id);

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
