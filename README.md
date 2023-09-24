# Laravel SMS Sender

[![Latest Version on Packagist](https://img.shields.io/packagist/v/variablesign/sms.svg?style=flat-square)](https://packagist.org/packages/variablesign/sms)
[![Total Downloads](https://img.shields.io/packagist/dt/variablesign/sms.svg?style=flat-square)](https://packagist.org/packages/variablesign/sms)
![GitHub License](https://img.shields.io/github/license/variablesign/sms)

A Laravel SMS Gateway Integration package for bulk SMS providers in **Ghana**. Below are the gateways that are currently supported and others added in future updates.

Available features:

-   Check remaining SMS balance or credits
-   Send SMS messages
-   Check SMS delivery status
-   Send OTP messages

Supported gateways:

-   [SMS Online GH](https://smsonlinegh.com)
-   [USMS-GH](https://usmsgh.com)
-   [mNotify](https://mnotify.com)
-   [Arkesel](https://arkesel.com)
-   More in future updates

## System Requirements

- PHP 7.2 or greater 
- Laravel 7 or greater

## Installation

You can install the package via composer:

```bash
composer require variablesign/sms
```

## Configuration

Publish the sms config by running this command after installation:

```bash
php artisan vendor:publish --provider="VariableSign\Sms\SmsServiceProvider" --tag="config"
```

Head to `config/sms.php` to start editing the configuration settings. Change the default gateway by setting the value to any of the gateways defined in the list of gateways. You can however ever switch between gateways in your code which will ignore the default config value.

```php
'default' => 'smsonlinegh',
```

The following shows the options for `smsonlinegh` gateway.

```php
'gatways' => [
    'smsonlinegh' => [
        'endpoints' => [
            'send' => 'https://api.smsonlinegh.com/v4/message/sms/send',
            'balance' => 'https://api.smsonlinegh.com/v4/report/balance',
            'report' => 'https://api.smsonlinegh.com/v4/report/message/delivery',
        ],
        'key' => 'Your API Key',
        'sender' => 'Your Sender ID',
        'verify' => false, // (optional) Disable SSL verification for non-https endpoints
        'timeout' => 15, // (optional) The connection timeout in seconds
    ],
    ...
]
```

## Usage

### Checking SMS balance
You can check your SMS balance or credits by using any of the following.

```php
use VariableSign\Sms\Facades\Sms;

// Using the default gateway
$response = Sms::balance();

// With another gateway
$response = Sms::via('mnotify')->balance();
```

Without facades

```php
use VariableSign\Sms\Sms;

$response = (new Sms)->via('mnotify')->balance();
```

With helper function

```php
$response = sms()->via('mnotify')->balance();
```

Returns an integer as the response or `0` as the default;

```php
250
```

### Sending SMS messages
You can send SMS messages by using any of the following.

```php
use VariableSign\Sms\Facades\Sms;

// Using the default gateway
$response = Sms::to(['2332xxxxxxxx','2332xxxxxxxx'])
    ->message('Hi, we just want to thank you for using our service.')
    ->send();

// With another gateway
$response = Sms::via('arkesel')
    ->to(['2332xxxxxxxx','2332xxxxxxxx'])
    ->message('Hi, we just want to thank you for using our service.')
    ->send();
```

Without facades

```php
use VariableSign\Sms\Sms;

$response = (new Sms)->via('arkesel')
    ->to(['2332xxxxxxxx','2332xxxxxxxx'])
    ->message('Hi, we just want to thank you for using our service.')
    ->send();
```

With helper function

```php
$response = sms()->via('arkesel')
    ->to(['2332xxxxxxxx','2332xxxxxxxx'])
    ->message('Hi, we just want to thank you for using our service.')
    ->send();
```

Returns `\Illuminate\Support\Collection` as the response;

```php
[
    {
        "id": "c61ff669-4bb1-41c1-97ea-11658dedafbd",
        "to": "2332xxxxxxxx",
        "message": "Hi, we just want to thank you for using our service.",
        "status": "submitted"
    },
    {
        "id": "572ae33d-3983-47a0-a1ac-6fc3efafac4f",
        "to": "2332xxxxxxxx",
        "message": "Hi, we just want to thank you for using our service.",
        "status": "submitted"
    }
]
```

### Checking SMS delivery status
You can check the delivery status of submitted messages by using their message `id`.

```php
use VariableSign\Sms\Facades\Sms;

// Using the default gateway
$response = Sms::report('c61ff669-4bb1-41c1-97ea-11658dedafbd');

// With another gateway
$response = Sms::via('arkesel')->report('c61ff669-4bb1-41c1-97ea-11658dedafbd');
```

Without facades

```php
use VariableSign\Sms\Sms;

$response = (new Sms)->via('arkesel')->report('c61ff669-4bb1-41c1-97ea-11658dedafbd');
```

With helper function

```php
$response = sms()->via('arkesel')->report('c61ff669-4bb1-41c1-97ea-11658dedafbd');
```

Returns `\Illuminate\Support\Collection` as the response;

```php
[
    {
        "id": "c61ff669-4bb1-41c1-97ea-11658dedafbd",
        "to": "2332xxxxxxxx",
        "status": "delivered"
    }
]
```

### Sending OTP messages
You can use our `otp()` method to generate and quickly send one-time-pin messages.

```php
use VariableSign\Sms\Facades\Sms;

// Can also be initialized without facades or with the helper function
$response = Sms::via('arkesel')
    ->to(['+2332xxxxxxxx'])
    ->otp('Password Reset', now()->addMinutes(5)->addSecond());
```

Returns `\Illuminate\Support\Collection` as the response;

```php
[
    {
        "id": "4180e0a9-71cb-41e2-aafe-1cb69c1545ea",
        "to": "2332xxxxxxxx",
        "message": "Your Password Reset OTP is 9826. It expires in 5 minutes.",
        "status": "submitted",
        "otp": "9826",
        "expires_at": "2022-06-08T20:00:53.000000Z"
    }
]
```

Without expiration time

```php
use VariableSign\Sms\Facades\Sms;

// Can also be initialized without facades or with the helper function
$response = Sms::via('arkesel')->to(['+2332xxxxxxxx'])->otp('Password Reset');
```

Returns `\Illuminate\Support\Collection` as the response;

```php
[
    {
        "id": "d570a041-a13c-4e11-8e78-0c7515729556",
        "to": "2332xxxxxxxx",
        "message": "Your Password Reset OTP is 9826.",
        "status": "submitted",
        "otp": "9826"
    }
]
```

Or with your own custom messages. Use the `:code` placeholder in your messages and it will be replaced with the generated OTP. You can generate a code length between `4` to `8`. The example below generates a code length of `6`.

```php
use VariableSign\Sms\Facades\Sms;

// Can also be initialized without facades or with the helper function
$response = Sms::via('arkesel')
    ->to(['+2332xxxxxxxx'])
    ->message('Your phone number verification code is :code.')
    ->otp(null, now()->addMinutes(5)->addSecond(), 6);
```

Returns `\Illuminate\Support\Collection` as the response;

```php
[
    {
        "id": "e1ee89b2-05c7-454a-bf52-3ffe243aee1b",
        "to": "2332xxxxxxxx",
        "message": "Your phone number verification code is 803501. It expires in 5 minutes.",
        "status": "submitted",
        "otp": "803501",
        "expires_at": "2022-06-08T19:49:36.000000Z"
    }
]
```

## Channel Usage

You can also send SMS messages through Laravel's notification class using `php artisan make:notification` command via our `SmsChannel::class` as the channel:

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use VariableSign\Sms\Channels\SmsChannel;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \VariableSign\Sms\Sms
     */
    public function toSms($notifiable)
    {
        return sms()
            ->via('smsonlinegh') // optional
            ->to($notifiable->phone)
            ->message('Your payment of 750.00 for order #10045 was successful.');
    }
}
```

Sending the notification:

```php
$user->notify(new PaymentNotification);
```

### Notification channel response 
You can learn how to retrieve the response through the `NotificationSent` event from the Notification section of the Laravel docs.

### Debugging
You can die dump the raw api response by adding the `dd()` method.

```php
use VariableSign\Sms\Facades\Sms;

// Returns the unformatted response from the api endpoint
$response = Sms::dd()->via('usmsgh')->balance();
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email variablesign@gmail.com instead of using the issue tracker.

## Credits

-   [Variable Sign](https://github.com/variablesign)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
