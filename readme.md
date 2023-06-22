# Xendit Laravel API

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Description

This PHP Package provides a convenient way to interact with the Xendit Laravel API using PHP Laravel. This package allows you to easily integrate Xendit payment functionality into your PHP applications.

## Installation

You can install the haaruuyaa\xendit-laravel-api PHP Package via Composer. Run the following command in your project directory:

```bash
composer require haaruuyaa/xendit-laravel-api
```
## Setup .env

You can put your API Key / Token in the .env file.
```dotenv
XENDIT_URL=https://api.xendit.co/
XENDIT_TOKEN=yourtoken
XENDIT_API_VERSION=2020-10-31
```

## Usage

```php
use Haaruuyaa\XenditApi\Facades\Ewallet;

// To create an ewallet payment
Ewallet::createPayment();
// To create an ewallet Void
Ewallet::createVoid($id);
// To create an ewallet Refund
Ewallet::createRefund($id);
// To get an update for the ewallet charge status
Ewallet::getPaymentStatus($id);

```