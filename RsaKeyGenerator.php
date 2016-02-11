<?php

$randomString = bin2hex(openssl_random_pseudo_bytes(200));
echo 'app key: ' . md5($randomString) . PHP_EOL;
$randomString = bin2hex(openssl_random_pseudo_bytes(200));
echo 'secret key: ' . md5($randomString) . PHP_EOL;

$config = array(
              "digest_alg" => "sha1",
              "private_key_bits" => 512,
              "private_key_type" => OPENSSL_KEYTYPE_RSA,
          );

// Create the private and public key
$res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
openssl_pkey_export($res, $privKey);

// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];
$pubKey = str_replace(array("\n", "-----BEGIN PUBLIC KEY-----", "-----END PUBLIC KEY-----"), "", $pubKey);

$privKey = str_replace(array("\n", "-----BEGIN RSA PRIVATE KEY-----", "-----END RSA PRIVATE KEY-----"), "", $privKey);

echo 'rsa public key: ' . $pubKey . PHP_EOL;
echo 'rsa private key: ' . $privKey . PHP_EOL;

