<?php

namespace Twala\Helpers;

use Web3p\EthereumUtil\Util;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

class Web3Helper {
    /**
     * Generate an Ethereum account and return its public and private keys.
     *
     * @return array<string, string> An associative array with keys 'public_key' and 'private_key'.
     */
    public function createAccount(): array {
        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'secp256k1'
        ];
        
        $res = openssl_pkey_new($config);
        
        if (!$res) {
            echo 'ERROR: Fail to generate private key. -> ' . openssl_error_string();
            exit;
        }
        
        // private key
        openssl_pkey_export($res, $priv_key);
        
        // public key
        $key_detail = openssl_pkey_get_details($res);
        $pub_key = $key_detail["key"];
        
        $priv_pem = PEM::fromString($priv_key);
        
        // convert to Elliptic Curve private key format
        $ec_priv_key = ECPrivateKey::fromPEM($priv_pem);
        
        // convert to ASN1 Structure
        $ec_priv_seq = $ec_priv_key->toASN1();
        
        // hex private key and public key
        $priv_key_hex = bin2hex($ec_priv_seq->at(1)->asOctetString()->string());
        $priv_key_len = strlen($priv_key_hex) / 2;
        $pub_key_hex = bin2hex($ec_priv_seq->at(3)->asTagged()->asExplicit()->asBitString()->string());
        $pub_key_len = strlen($pub_key_hex) / 2;
        
        // derive eth address from public key
        // remove the leading 0x04 for hashing
        $pub_key_hex_2 = substr($pub_key_hex, 2);
        $pub_key_len_2 = strlen($pub_key_hex_2) / 2;
        
        $hash = Keccak::hash(hex2bin($pub_key_hex_2), 256);
        
        // get the last 20 bytes as wallet address
        $address = '0x' . substr($hash, -40);
        $privateKey = '0x' . $priv_key_hex;

        return [
            'public_key' => $address,
            'private_key' => $privateKey
        ];
    }

    /**
     * Sign a message with the provided private key and return the message, message hash, v, r, and s.
     *
     * @param string $message
     * @param string $privateKey
     * @return array<string, string>
     */
    public static function sign(string $message, string $privateKey) {
        $util = new Util();
        // append Ethereum (EIP-191) specific identifier
        $messageToSign = "\x19Ethereum Signed Message:\n" . strlen($message) . $message;
        // hash the message and add leading 0x
        $messageHash = '0x' . Keccak::hash($messageToSign, 256);
        // generate signature using ECDSA signature
        $signature = $util->ecsign($privateKey, $messageHash);

        return [
            'message' => $message,
            'message_hash' => $messageHash,
            'v' => $signature->recoveryParam,
            'r' => '0x' . $signature->r->toString(16),
            's' => '0x' . $signature->s->toString(16),
        ];
    }

    /**
     * Recover and return the Ethereum wallet address from the message hash, v, r, and s.
     *
     * @param string $messageHash
     * @param int $v
     * @param string $r
     * @param string $s
     * @return string
     */
    public static function recover(string $messageHash, int $v, string $r, string $s): string {
        $util = new Util();
        // recover public key from signature digest
        $publicKey = $util->recoverPublicKey($messageHash, $r, $s, $v - 35);
        // convert public key to wallet address
        $address = $util->publicKeyToAddress($publicKey);

        return $address;
    }
}