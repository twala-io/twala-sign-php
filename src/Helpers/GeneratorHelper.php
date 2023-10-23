<?php

namespace Twala\Helpers;

use Ramsey\Uuid\Uuid;

class GeneratorHelper {
    /**
     * Generate and return a nonce as a string.
     *
     * @return string
     */
    public function generateNonce(): string {
        $nonce = Uuid::uuid1()->toString();
        return $nonce;
    }
}
