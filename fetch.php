<?php 
require_once('Helpers/functions.php');

if (isset($_POST['fetch'])) {
    $html = exec('node --no-deprecation /srv/http/bi0s/Helpers/top.js');
    $top_5 = explode(',', $html);
    $assoc = [];

    for ($i = 0; $i < count($top_5); $i++) {
        $current = explode('. ', $top_5[$i]);
        $assoc[$current[0]] = $current[1];
    }

    $json = json_encode($assoc);
    if (isset($json)) {
        echo $json;
        http_response_code(200);
        die();
    } else {
        http_response_code(500);
    }

} else {
    http_response_code(404);
}

?>