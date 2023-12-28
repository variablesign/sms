# Changelog

All notable changes to `sms` will be documented in this file

## 2.0.3

- Fixed `Arkesel` driver return `0` on error when checking balance instead of `null`

## 2.0.2

- Updated drivers to match changes from v2.0.1

## 2.0.1

- Updated drivers to match changes from v2.0.0

## 2.0.0

- The `balance` method now returns `null` as default instead of `0`. This helps to differentiate between a failed connection and `0` balance

## 1.2.0

- Added `SmsChannel` class to send messages through `Notification` class

## 1.1.0

- Added Laravel 7 support
  
## 1.0.0

- Check remaining SMS balance or credits
- Send SMS messages
- Check SMS delivery status
- Send OTP messages