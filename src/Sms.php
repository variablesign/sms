<?php

namespace VariableSign\Sms;

use Exception;
use Illuminate\Support\Collection;
use VariableSign\Sms\Contracts\Driver;

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
        if ($this->config('gateways.' . $this->gateway. '.balance')) {
            return 0;
        }

        $driver = $this->getDriverInstance();

        return $driver->dd($this->debug)->balance();
    }

    public function send(): Collection
    {
        if ($this->config('gateways.' . $this->gateway. '.send')) {
            return collect([]);
        }

        $driver = $this->getDriverInstance();
        $recipients = $this->builder->getRecipients();
        $message = $this->builder->getMessage(); 
        $response = $driver->dd($this->debug)->send($recipients, $message);

        return collect($response);
    }

    public function report(string|int $id): Collection
    {
        if ($this->config('gateways.' . $this->gateway. '.report')) {
            return collect([]);
        }

        $driver = $this->getDriverInstance();
        $response = $driver->dd($this->debug)->report($id);

        return collect($response);
    }

    public function otp(string $name = null, string|null $expiryDate = null, int $codeLength = 4): Collection
    {
        $codeLength = min(max($codeLength, 4), 8);
        $numbers = '0123456789';
        $code = substr(str_shuffle($numbers), 0, $codeLength);
        $mergeData = [
            'otp' => $code
        ];

        if ($this->builder->getMessage()) {
            $message = __($this->builder->getMessage(), [
                'code' => $code
            ]);
        } else {
            $message = __('Your :name OTP is :code.', [
                'name' => $name,
                'code' => $code
            ]);
        }

        if ($expiryDate) {
            $expiryDate = now()->parse($expiryDate);

            $message .= __(' It expires in :time.', [
                'time' => $expiryDate->longAbsoluteDiffForHumans()
            ]);
            
            $mergeData = array_merge($mergeData, [
                'expires_at' => $expiryDate
            ]);
        }

        $this->message($message);

        $driver = $this->getDriverInstance();
        $recipients = $this->builder->getRecipients();
        $message = $this->builder->getMessage(); 
        $response = $driver->dd($this->debug)->send($recipients, $message, $mergeData);

        return collect($response);
    }

    protected function getDriverInstance(): Driver
    {
        if (!$this->driver) {
            throw new Exception("SMS gateway or driver not found.");
        }

        $class = $this->driver;

        return new $class($this->config('gateways.' . $this->gateway));
    }

    protected function getDriver(): ?string
    {
        return $this->driver;
    }
}
