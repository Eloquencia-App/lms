<?php

include 'config.php';

$req = $db->prepare('DELETE FROM tokens WHERE token = ?');
$req->execute(array($_COOKIE['token']));

setcookie('token', '', time() - 3600, null, null, false, true);

header('Location: ./login.php?error=disconnected');