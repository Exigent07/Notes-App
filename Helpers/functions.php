<?php 
require_once('encryption.php');

function verify($encrypted, $password) {
    $encryptPassword = encrypt($password);

    if ($encryptPassword === $encrypted) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function increase_cookie($user) {
    if (isset($_COOKIE[$user])){
        $current = decrypt($_COOKIE[$user]);
        $value_cookie = ((int)$current) + 1;
        unset($_COOKIE[$user]);
        setrawcookie($user, encrypt(strval($value_cookie)));
    } else {
        $value_cookie = 1;
        setrawcookie($user, encrypt(strval($value_cookie)));
    }

    return TRUE;
}

function sanitize($string) {
    $result = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    $char   = "/[!@#$%^*()_+={}\[\]:;,?\/~`-]/";
    $result = preg_replace($char, "", $result);

    return $result;
}
?>