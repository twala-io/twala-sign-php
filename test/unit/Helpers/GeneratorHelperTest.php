<?php

namespace Twala\Test\Unit\Helpers;

use Twala\Helpers\GeneratorHelper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class GeneratorHelperTest extends TestCase
{
    public function testGenerateNonce()
    {
        $generatorHelper = new GeneratorHelper();
        $nonce = $generatorHelper->generateNonce();

        $this->assertTrue(Uuid::isValid($nonce));
    }
}