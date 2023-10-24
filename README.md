# Twala PHP Library

This is the official Twala SDK for applications written in PHP.

### Installation

Simply clone this repository and load it with a [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloader.


You can also install the latest version of Twala SDK by issuing the following command:

```
composer require twala-io/twala-php
```

This SDK has no external dependencies in production. This means you can use this library in any PHP project even when you don’t have access to Composer.

### Requirements

This SDK requires PHP 7.3 or later with the following extensions:

- sop/asn1
- sop/crypto-types
- sop/crypto-encoding
- web3p/ethereum-util
- kornrunner/keccak
- ramsey/uuid

Supported PHP [Versions](https://www.php.net/supported-versions.php): 7.3, 7.4, 8.0, 8.1, 8.2.

### Getting Started
Please follow the [installation procedure](#installation) then create an entrypoint PHP file with the following:

```
<?php

// Import a PSR-4 autoloader
require_once(__DIR__ . '/autoload.php');

// Set your API Keys 👇 here
$appUuid = 'your_app_uuid'; // Replace with your app UUID
$appSecret = 'your_app_secret'; // Replace with your app secret

// Instantiate Twala SDK
$sdk = new \Twala\Sdk($appUuid, $appSecret);

// Generate account keys
$account = $sdk->generateAccountKeys();

// Generate nonce / uuid
$uuid = $sdk->generateNonce();

// Sign document uuid
$signature = $sdk->signDocumentUuid($uuid, $account['private_key']);

// Recover address from signature digest
$recover = $sdk->recoverAddress(
    $signature['message_hash'],
    $signature['v'],
    $signature['r'],
    $signature['s']
);
```

API keys are optional when creating a new instance of `\Twala\Sdk()`.

> Please note that some parts of the API may require using your own API key.

### Tests

To run the unit tests, use:

```
composer install
vendor/bin/phpunit
```

### Troubleshooting

Should you face any issues, feel free to contribute to our troubleshooting process by forking the SDK and submitting pull requests for any changes. For reporting issues and tracking progress, create new issues within this GitHub repository.

### Support

The most recent major release of `twala-php` includes both new functionality and bug fixes. To take advantage of new features and bug patches, including those for security vulnerabilities, if you are using an earlier major version, we advise you to upgrade to the most recent version. Older major versions of the package will still be usable but won't receive updates.
