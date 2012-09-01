<?php

require_once '../bootstrap.php';

if (isset($_SESSION['adn_access_token'])) {
    unset($_SESSION['adn_access_token']);
}

header('Location: index.php');
