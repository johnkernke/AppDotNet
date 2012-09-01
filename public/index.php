<?php

use TehBanana\AppDotNet\Users;
use TehBanana\AppDotNet\Posts;

require_once '../bootstrap.php';

$adn_users = new Users($adn);
$adn_posts = new Posts($adn);

echo '<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>PHP AppDotNet Library</title>
        <style>
        body {
            background: #f8f8f8;
            color: #222222;
            font-family: "Helvetica Neue", Helvetica, Arial, sans;
            font-size: 10pt;
        }

        div.column-half {
            box-sizing: border-box;
            display: inline-block;
            padding: 10px;
            vertical-align: top;
            width: 50%;
        }
        </style>
    </head>
    <body>
';

if (!isset($_SESSION['adn_access_token'])) {
    $auth_url = $adn->getAuthUrl($app_adn_scopes);
    echo '<a href="' . $auth_url . '">Login with App.net</a>';
} else {

    try {
        $current_user = $adn_users->getUser()->data;
    } catch (Exception $e) {
        echo '<pre>' . var_export($e, true) . '</pre>';
    }

    echo 'Welcome @' . $current_user->username . ', <a href="signout.php">Signout</a>';
    echo '<br><br>';

    if (isset($_POST['post_message'])) {
        try {
            $create_post = $adn_posts->createPost($_POST['post_message']);
            echo '<b>Successfully created post!</b>';
        } catch (Exception $e) {
            echo '<b>Error creating post!</b>';
        }
        echo '<br><br>';
    }

    echo '<b>Create Post:</b> ';
    echo '<form action="index.php">';
    echo '<textarea name="post_message" placeholder="Post Message" cols="75" row="5"></textarea>';
    echo '<br>';
    echo '<input type="submit" value="post">';
    echo '</form>';
    echo '<br><br>';

    echo '<div class="column-half">';
    echo '<h2>Global Stream</h2>';

    $global_stream = $adn_posts->getGlobalStream()->data;
    foreach ($global_stream as $post) {
        if (isset($post->is_deleted)) {
            continue;
        }

        $user = $post->user;
        echo '<p>';
        echo '<b>@' . $user->username . '</b>: ';
        echo $post->html;
        echo '</p>';
    }

    echo '</div>';

    echo '<div class="column-half">';
    echo '<h2>Personal Stream</h2>';

    $personal_stream = $adn_posts->getPersonalStream()->data;
    foreach ($personal_stream as $post) {
        if (isset($post->is_deleted)) {
            continue;
        }

        $user = $post->user;
        echo '<p>';
        echo '<b>@' . $user->username . '</b>: ';
        echo $post->html;
        echo '</p>';
    }

    echo '</div>';
}

echo '<br><br><b>Request debug info:</b>';
echo '<br>Request Limit: ' . $adn->getRateRequestLimit();
echo '<br>Requests Remaining: ' . $adn->getRequestsRemaining();
echo '<br>Request Reset Time: ' . $adn->getRequestResetTime();
echo '<br>Requests per second allowed: ' . number_format($adn->getRequestsRemaining() / $adn->getRequestResetTime(), 2);

echo '</body>
</html>';
