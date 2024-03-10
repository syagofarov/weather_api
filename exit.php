<?php 
    setcookie('user', $user['name'], time() - 3600, "/");
    setcookie('user_id', $user['id'], time() - 3600, "/");
    header ('Location: /');
?>