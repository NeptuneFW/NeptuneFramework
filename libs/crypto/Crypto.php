<?php

namespace Libs\Crypto;

class Crypto
{
    static public $compress = true;
    static public $base64_encode = true;
    static public $url_safe = true;
    static public $use_keygen = true;
    static public $keygen_length = 32;
    static public $test_decrypt_before_return = false;
    static public $key = null;
    static private $CryptAesClass = null;

    static public function encrypt($data, $key = null)
    {
        self::__init($key);
        return self::$CryptAesClass->encrypt($data, $key);
    }
    static public function decrypt($data, $key = null)
    {
        self::__init($key);
        return self::$CryptAesClass->decrypt($data, $key);
    }
    static public function keygen($clear_text, $length = 32) {
        self::__init(null);
        return self::$CryptAesClass->keygen($clear_text, $length);
    }
    static private function __init($key)
    {
        if(empty($key))
        {
            $key = self::$key;
        }
        $options = array(
            'compress'          => self::$compress,
            'base64_encode'     => self::$base64_encode,
            'url_safe'          => self::$url_safe,
            'use_keygen'        => self::$use_keygen,
            'keygen_length'     => self::$keygen_length,
            'test_decrypt_before_return' => self::$test_decrypt_before_return,
        );
        if(!self::$CryptAesClass)
        {
            self::$CryptAesClass = new CryptAesClass($key,$options);
        }
    }
}
class CryptAesClass
{
    public $options = null;
    public $key = null;
    public $debug = true;

    function __construct($key = null, $options = [])
    {
        if(!function_exists('mcrypt_decrypt'))
        {
            throw new Exception("Gerekli PHP sürümünüz 'mcrypt' fonksiyonunu desteklememektedir - http://php.net/manual/en/book.mcrypt.php");
        }
        $this->options = array_merge(
            [
                'compress' => true,
                'base64_encode' => true,
                'url_safe' => true,
                'use_keygen' => true,
                'keygen_length' => 32,
                'test_decrypt_before_return' => false,
            ],
            (array)$options
        );
        if(!empty($key))
        {
            $this->key = $key;
        }
    }
    public function encrypt($data_in, $key = null)
    {
        if (empty($data_in))
        {
            return $data_in;
        }
        if(empty($key))
        {
            $key = $this->key;
        }
        $data = serialize($data_in);
        if ($this->options['compress'])
        {
            $data = gzcompress($data);
        }
        $data = $this->_encryptData($data,$key);
        if ($this->options['base64_encode'])
        {
            $data = base64_encode($data);
            if ($this->options['url_safe'])
            {
                $data = strtr($data, '+/=', '-_,');
            }
        }
        if ($this->options['test_decrypt_before_return'])
        {
            if ($data_in !== $this->decrypt($data,$key))
            {
                throw new Exception('Şifrelenmiş veriler şifrelenmemiş veriler ile eşleşmemektedir!');
            }
        }
        return $data;
    }
    public function decrypt($data_in, $key = null)
    {
        if (empty($data_in))
        {
            return $data_in;
        }
        if(empty($key))
        {
            $key = $this->key;
        }
        $data = $data_in;
        if ($this->options['url_safe'] && $this->options['base64_encode'])
        {
            $data = strtr($data, '-_,', '+/=');
        }
        if ($this->options['base64_encode'])
        {
            $data = base64_decode($data);
        }
        $data = $this->_decryptData($data, $key);
        if ($this->options['compress'])
        {
            $data = gzuncompress($data);
        }
        return unserialize($data);
    }
    public function keygen($clear_text, $length = null)
    {
        if(empty($length))
        {
            $length = $this->options['keygen_length'];
        }
        $first_character_position = 20;
        $string = base64_encode(base64_encode(md5($clear_text, true) . md5($clear_text, true)));
        return substr($string,$first_character_position,$length);
    }
    protected function _decryptData($data_with_iv_suffix,$key)
    {
        if($this->options['use_keygen'])
        {
            $key = $this->keygen($key,$this->options['keygen_length']);
        }
        $this->_preChecks($key);
        $data = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $key,
            substr($data_with_iv_suffix,0,(strlen($data_with_iv_suffix) - 16)),
            MCRYPT_MODE_CBC,
            substr($data_with_iv_suffix,(strlen($data_with_iv_suffix) - 16), 16)
        );
        if($this->options['compress'])
        {
            return $data;
        }
        return rtrim($data, "\0");
    }
    protected function _encryptData($data,$key)
    {
        if($this->options['use_keygen'])
        {
            $key = $this->keygen($key, $this->options['keygen_length']);
        }
        $this->_preChecks($key);
        $iv = $this->keygen(md5(SECRET), 16);
        $data = mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            $key,
            $data,
            MCRYPT_MODE_CBC,
            $iv
        );
        return $data . $iv;
    }
    protected function _preChecks($key)
    {
        if(empty($key))
        {
            throw new Exception('Cryp için $key değeri tanımlanmamış.');
        }
        $key_length = (strlen($key) * 8);
        if('128' != $key_length && '192' != $key_length && '256' != $key_length) {
            throw new Exception('AES tipi şifreleme için uygun olmayan anahtar uzunluğu - anahtar değeri *, 128, 192 veya 256 bit olmalıdır; bu, 16, 24 veya 32 uzunluğunda bir dize karakteri olmalıdır anlamına gelir * ZORUNLU *');
        }
        return true;
    }
}
class CryptClass
{
    private $CryptAesClass = null;
    public function __construct($cipher, $key, $mode, $iv)
    {
        $this->CryptAesClass = new CryptAesClass($key);
    }
    public function encrypt($data)
    {
        return $this->CryptAesClass->encrypt($data);
    }
    public function decrypt($data)
    {
        return $this->CryptAesClass->decrypt($data);
    }
}