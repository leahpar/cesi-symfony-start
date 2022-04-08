<?php

namespace App\Service;

class CryptService
{
    //define('CIPHERING', "AES-256-CBC"); //MODE DE CRYPTAGE
    //define('INIT_VECTOR', "2511860808760000"); //VECTEUR D'INITIALISATION
    //define('ENCRYPTION_KEY', ""); //CLE PRIVEE
    //define('CRYPT_OPTIONS', 0); //OPTIONS DE CRYPTAGES
    //define('IV_LENGTH', openssl_cipher_iv_length(CIPHERING)); //LONGEUR DU VECTEUR D'INITALISATION

    private $CIPHERING;
    private $INIT_VECTOR;
    private $ENCRYPTION_KEY;
    private $CRYPT_OPTIONS;
    private $IV_LENGTH;

    public function __construct(
            string $CIPHERING,
            string $INIT_VECTOR,
            string $ENCRYPTION_KEY,
            string $CRYPT_OPTIONS,
    ){
        $this->CIPHERING = $CIPHERING;
        $this->INIT_VECTOR = $INIT_VECTOR;
        $this->ENCRYPTION_KEY = $ENCRYPTION_KEY;
        $this->CRYPT_OPTIONS = $CRYPT_OPTIONS;
        $this->IV_LENGTH = openssl_cipher_iv_length($CIPHERING);
    }

    //Fonction de chiffrage d'une chaine de caracètres.
    function encrypt($string)
    {
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($string, $this->CIPHERING, $this->ENCRYPTION_KEY, $this->CRYPT_OPTIONS, $this->INIT_VECTOR);
        return $encryption;
    }

    //Fonction de déchiffrage
    function decrypt($encryption)
    {
        $decryption = openssl_decrypt($encryption, $this->CIPHERING, $this->ENCRYPTION_KEY, $this->CRYPT_OPTIONS, $this->INIT_VECTOR);
        return $decryption;
    }


}
