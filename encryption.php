<?php 
    function encrypt($plain_text) : string {
        $cipher = "AES-256-CBC";
        $secrert = "You cant hack it so, try hacking";
        $options = 0;
        $iv = str_repeat("X", openssl_cipher_iv_length($cipher));
    
        $cipher_text = openssl_encrypt($plain_text, $cipher, $secrert, $options, $iv);

        return $cipher_text;
    }

    function decrypt($cipher_text) : string {
        $cipher = "AES-256-CBC";
        $secrert = "You cant hack it so, try hacking";
        $options = 0;
        $iv = str_repeat("X", openssl_cipher_iv_length($cipher));
    
        $plain_text = openssl_decrypt($cipher_text, $cipher, $secrert, $options, $iv);

        return $plain_text;
    }
?>