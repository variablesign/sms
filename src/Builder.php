<?php

namespace VariableSign\Sms;

class Builder
{
    protected array $recipients;

    protected ?string $gateway;

    protected string $message;

    public function via(?string $gateway): self
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function to(array|string $recipients): self
    {
        $this->recipients = is_array($recipients) ? $recipients : [$recipients];

        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getGateway(): ?string
    {
        return $this->gateway;
    }
}