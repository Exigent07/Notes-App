<?php 
require_once('encryption.php');
require_once('connect.php');

ini_set('session.save_path', '/srv/http/sessions/');

function isBlocked($conn) {
    $row = query($conn, "SELECT * FROM waf where username = 'blocked' and ip = ? ", $_SERVER['REMOTE_ADDR']);

    if ($row) {
        header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
        die();
    }
}

function redos($input) {
    if (strlen($input) > 14) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function verify($encrypted, $password) {
    $encryptPassword = encrypt($password);
    $conn = connect();

    isBlocked($conn);

    if ($encryptPassword === $encrypted) {
        return TRUE;
    }
    else {
        if (isset($_COOKIE['failed'])) {
            $value = decrypt($_COOKIE["failed"]);
            $value_array = explode(",", $value);

            if ($value_array[0] === $_SERVER['REMOTE_ADDR']) {
                $fail = ((int)$value_array[1]);
                if ($fail >= 5) {
                    insert($conn, "waf", array("blocked", $_SERVER['REMOTE_ADDR'], $fail));
                    header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
                    die();
                } else {
                    unset($_COOKIE["failed"]);
                    setrawcookie("failed", encrypt($_SERVER['REMOTE_ADDR'] . "," . strval($fail + 1)), time() + 15);
                    return FALSE;
                }
            }
        } else {
            setrawcookie("failed", encrypt($_SERVER['REMOTE_ADDR'] . ",1"), time() + 15);
            return FALSE;
        }
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

function userAgent() {
    $header = getallheaders();
    $user_agent = $header['User-Agent'];

    $allowed = array("Mozilla/5.0", "Chrome/51.0.2704.106", "Opera/9.60", "Opera/9.80");
    $allow = TRUE;

    for ($i = 0; $i < count($allowed); $i++) {
        $isThere = strpos($user_agent, $allowed[$i]);
        if ($isThere !== FALSE) {
            $allow = TRUE;
            break;
        } else {
            $allow = FALSE;
            break;
        }
    }

    if ($allow) {
        return TRUE;
    } else {
        echo "Unauthorized!!";
        header($_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
        die();
    }
}
?>