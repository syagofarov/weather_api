<?php
    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

    $pass = password_hash($pass, PASSWORD_DEFAULT);

    $db_connection = pg_connect("host=pg3.sweb.ru dbname=saviorcheg user=saviorcheg password=Ss859756211");

    if (!$db_connection) {
        die("Failed to connect to PostgreSQL");
    }

    $query = "SELECT * FROM users WHERE login = '$login'";
    $result = pg_query($db_connection, $query);
    $user = pg_fetch_assoc($result);

    if (!$user || !password_verify($_POST['pass'], $user['pass'])) {
        echo "Такой пользователь не найден";
        exit();
    }

    setcookie('user', $user['name'], time() + 3600, "/");
    setcookie('user_id', $user['id'], time() + 3600, "/");

    pg_close($db_connection);

    header('Location: /');
?>