<?php

require_once '../bootstrap.php';

if ($adn->getAccessToken()) {
    header('Location: index.php');
}

if (isset($_GET['code'])) {
    $access_token = $adn->getAccessToken($_GET['code']);
    if ($access_token != '') {
        $_SESSION['adn_access_token'] = $access_token;
        header('Location: index.php');
    } else {
        echo 'Unable to get access token.';
    }
} else {
    echo 'No code received.';
}
