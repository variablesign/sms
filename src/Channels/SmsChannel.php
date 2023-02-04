<?php

namespace VariableSign\Sms\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'routeNotificationSmsLog')) {
            $id = $notifiable->routeNotificationSmsLog($notifiable);
        } else {
            $id = $notifiable->getKey();
        }

        $instance = method_exists($notification, 'toSms')
            ? $notification->toSms($notifiable)
            : $notification->toArray($notifiable);

        if (empty($instance)) {
            return;
        }

        return app('sms')
            ->via($instance->getGateway())
            ->to($instance->getRecipients())
            ->message($instance->getMessage())
            ->send();
    }
}