<?php

namespace Twala\Test\Unit\Helpers;

use Twala\Helpers\CryptoHelper;
use PHPUnit\Framework\TestCase;

class CryptoHelperTest extends TestCase
{
    public function testSignDataHMAC()
    {
        $cryptoHelper = new CryptoHelper();
        $data = 'sample_data';
        $key = 'sample_key';
        $expectedHash = base64_encode(hash_hmac('sha256', $data, $key, true));

        $result = $cryptoHelper->signDataHMAC($data, $key);

        $this->assertEquals($expectedHash, $result);
    }

    public function testVerifySignatures()
    {
        $cryptoHelper = new CryptoHelper();
        $signature = 'signature';
        $webhookSignature = 'signature';

        $this->assertTrue($cryptoHelper->verifySignatures($signature, $webhookSignature));
    }
}