<?php

namespace Twala\Test\Unit;

use PHPUnit\Framework\TestCase;
use Twala\Sdk;

class SdkTest extends TestCase
{
    protected $appUuid = 'your_app_uuid';
    protected $appSecret = 'your_app_secret';
    protected $sdk;

    protected function setUp(): void
    {
        // Instantiate the Sdk class with test data
        $this->sdk = new Sdk($this->appUuid, $this->appSecret);
    }

    public function testGenerateNonce()
    {
        $nonce = $this->sdk->generateNonce();
        $this->assertNotEmpty($nonce);
        $this->assertIsString($nonce);
    }

    public function testGenerateAccountKeys()
    {
        $accountKeys = $this->sdk->generateAccountKeys();
        $this->assertArrayHasKey('public_key', $accountKeys);
        $this->assertArrayHasKey('private_key', $accountKeys);
    }

    public function testSignDocumentUuid()
    {
        $uuid = $this->sdk->generateNonce();
        $accountKeys = $this->sdk->generateAccountKeys();
        $privateKey = $accountKeys['private_key'];
        $signature = $this->sdk->signDocumentUuid($uuid, $privateKey);
        $this->assertArrayHasKey('message', $signature);
        $this->assertArrayHasKey('message_hash', $signature);
        $this->assertArrayHasKey('v', $signature);
        $this->assertArrayHasKey('r', $signature);
        $this->assertArrayHasKey('s', $signature);
    }

    public function testRecoverAddress()
    {
        $expectedAddress = '0x4f3a0ce437280ea2df13035f2231651c02b2613a';
        $messageHash = '0x8d5760763b3f8e51bca9e82963b2674fee208075979936d7f23a03394b50db7c';
        $v = 35;
        $r = '0x23784b8aa49f13e4c50d705fdffef394e2d30aaabcc16ed301fe998c497589ec';
        $s = '0x4e71de5bd101868f90e24540f9d5b87bfaa9fed87c281fa3f2ee930a3e8b16e6';
        $recoveredAddress = $this->sdk->recoverAddress($messageHash, $v, $r, $s);
        $this->assertNotEmpty($recoveredAddress);
        $this->assertIsString($recoveredAddress);
        $this->assertEquals($expectedAddress, $recoveredAddress);
    }

    public function testGenerateWebhookSignature()
    {
        $expectedHash = 'xdeDR8VZpweDIi8C9D5Fv1fqkvdyNt1/uahcB0872LE=';
        $stringifiedRequestBody = 'password=test_password';
        $webhookSecret = '8uRhAeH89naXfFXKGOEj';
        $signature = $this->sdk->generateWebhookSignature($stringifiedRequestBody, $webhookSecret);
        $this->assertNotEmpty($signature);
        $this->assertIsString($signature);
        $this->assertEquals($expectedHash, $signature);
    }

    public function testVerifyWebhookSignature()
    {
        $headerSignature = 'Aq+1YwSQLGVvy3N83QPeYgW7bUAdooEu/ZstNqCK8Vk=';
        $webhookSignature = 'Aq+1YwSQLGVvy3N83QPeYgW7bUAdooEu/ZstNqCK8Vk=';
        $result = $this->sdk->verifyWebhookSignature($headerSignature, $webhookSignature);
        $this->assertTrue($result);
    }
}