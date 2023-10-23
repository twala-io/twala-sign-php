<?php

namespace Twala\Test\Unit\Helpers;

use Twala\Helpers\Web3Helper;
use PHPUnit\Framework\TestCase;

class Web3HelperTest extends TestCase
{
    public function testCreateAccount()
    {
        $web3Helper = new Web3Helper();
        $account = $web3Helper->createAccount();

        // Check if the account array contains the expected keys
        $this->assertArrayHasKey('public_key', $account);
        $this->assertArrayHasKey('private_key', $account);

        // Validate the format of the public and private keys
        $this->assertStringStartsWith('0x', $account['public_key']);
        $this->assertStringStartsWith('0x', $account['private_key']);
    }

    public function testSignAndRecover()
    {
        $web3Helper = new Web3Helper();
        $message = 'sample_message';
        $account = $web3Helper->createAccount();
        $privateKey = $account['private_key'];

        $signatureData = Web3Helper::sign($message, $privateKey);
        $recoveredAddress = Web3Helper::recover(
            $signatureData['message_hash'],
            $signatureData['v'],
            $signatureData['r'],
            $signatureData['s']
        );

        $this->assertEquals($account['public_key'], $recoveredAddress);
    }
}