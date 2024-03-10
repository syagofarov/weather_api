<?php
    require 'vendor/autoload.php';
    use Ramsey\Uuid\Uuid;

    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

    if (mb_strlen($login) < 2 || mb_strlen($login) > 50) {
        echo "Недопустимая длина логина (от 2 до 50 символов)";
        exit();
    } else if (mb_strlen($name) < 2 ||  mb_strlen($name) > 25) {
        echo "Недопустимая длина имени (от 2 до 25 символов)";
        exit();
    } else if (mb_strlen($pass) < 2 ||  mb_strlen($pass) > 10) {
        echo "Недопустимая длина пароля (от 2 до 10 символов)";
        exit();
    }

    // Хэширование пароля с использованием password_hash
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Подключение к базе данных PostgreSQL
    $db_connection = pg_connect("host=pg3.sweb.ru dbname=saviorcheg user=saviorcheg password=Ss859756211");

    if (!$db_connection) {
        die("Failed to connect to PostgreSQL");
    }

    $token = bin2hex(random_bytes(16)); // Генерация случайного токена
    // Подготовленный запрос для безопасного вставки данных
    $query = "INSERT INTO users (id, login, pass, token, name) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($db_connection, $query, array(Uuid::uuid4(), $login, $hashed_password, $token, $name));

    // Проверка успешности выполнения запроса
    if (!$result) {
        echo "Ошибка при добавлении пользователя";
        exit();
    }

    // Закрываем соединение с базой данных
    pg_close($db_connection);

    header('Location: /');
?>