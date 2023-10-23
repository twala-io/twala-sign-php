<?php

namespace Twala\Helpers;

class CryptoHelper {
    /**
     * Sign data using HMAC and return the result as a base64-encoded string.
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    public function signDataHMAC(string $data, string $key): string {
        $hash = hash_hmac('sha256', $data, $key, true);
        return base64_encode($hash);
    }

    /**
     * Verify signatures match.
     *
     * @param string $timingSafeEqual
     * @param string $webhookSignature
     * @return bool
     */
    public function verifySignatures(string $timingSafeEqual, string $webhookSignature): bool {
        return $timingSafeEqual == $webhookSignature;
    }
}
