<?php
/*
Main encryption for Frindse.com
Copyright @ james Latten
*/
class Encryption
{
    private $salt_length = 12;
    public $salt_key = 'sitelyftkeyaccess12345678910cool';

	/* 
		This function will create a unique and random hash!
	*/
    public function randomHash()
    {
        return substr(md5(uniqid(rand(), true)), 0, 20);
    }
	
	/*
		This is the main function that will encrypt the string and return it back to you in its encrypted state. Its using the MCrypt library built into PHP
	*/
    public function encryptText($data)
    {
        $data = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->salt_key, $data, MCRYPT_MODE_ECB)));
        return $data;
    }

	/*
		This will reverse the encryption here. You pass in the encrypted string to this variable and then it will give you the original text. it just uses the mcrypt_decrypt functio nto reverse the encryption
	*/
    public function decryptText($data)
    {
        $data = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->salt_key, base64_decode($data), MCRYPT_MODE_ECB));
        return $data;
    }
}