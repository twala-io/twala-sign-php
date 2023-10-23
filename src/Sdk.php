<?php

namespace Twala;

use Twala\Helpers\GeneratorHelper;
use Twala\Helpers\CryptoHelper;
use Twala\Helpers\Web3Helper;

class Sdk {
    protected $appUuid;
    protected $appSecret;
    protected $generatorHelper;
    protected $cryptoHelper;
    protected $web3Helper;

    /**
     * Twala/Sdk constructor.
     *
     * @param string $appUuid
     * @param string $appSecret
     * @param string $provider
     */
    public function __construct($appUuid, $appSecret, $provider = 'https://ethereum.publicnode.com') {
        $this->appUuid = $appUuid;
        $this->appSecret = $appSecret;
        $this->web3Helper = new Web3Helper();
        $this->cryptoHelper = new CryptoHelper();
        $this->generatorHelper = new GeneratorHelper();
    }

    /**
     * Generate nonce.
     *
     * @return string
     */
    public function generateNonce(): string {
        return $this->generatorHelper->generateNonce();
    }

    /**
     * Generate Ethereum account keys.
     *
     * @return array
     */
    public function generateAccountKeys(): array {
        $account = $this->web3Helper->createAccount();
        return $account;
    }

    /**
     * Sign document UUID using private key.
     *
     * @param string $uuid
     * @param string $privateKey
     * @return array
     */
    public function signDocumentUuid(string $uuid, string $privateKey): array {
        $signature = $this->web3Helper->sign($uuid, $privateKey);
        return $signature;
    }

    /**
     * Recover and return the Ethereum wallet address from message hash, v, r, and s.
     *
     * @param string $messageHash
     * @param int $v
     * @param string $r
     * @param string $s
     * @return string
     */
    public function recoverAddress(string $messageHash, int $v, string $r, string $s): string {
        $recoveredAddress = $this->web3Helper->recover($messageHash, $v, $r, $s);
        return $recoveredAddress;
    }

    /**
     * Generate webhook signature.
     *
     * @param string $stringifiedRequestBody
     * @param string $webhookSecret
     * @return string
     */
    public function generateWebhookSignature(string $stringifiedRequestBody, string $webhookSecret): string {
        return $this->cryptoHelper->signDataHMAC($stringifiedRequestBody, $webhookSecret);
    }

    /**
     * Verify webhook signature if match.
     *
     * @param string $headerSignature
     * @param string $webhookSignature
     * @return bool
     */
    public function verifyWebhookSignature(string $headerSignature, string $webhookSignature): bool {
        return $this->cryptoHelper->verifySignatures($headerSignature, $webhookSignature);
    }
}
