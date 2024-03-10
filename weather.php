<?php
header('Content-Type: application/json');

if (!isset($_COOKIE['user_id'])) {
    echo json_encode(['error' => 'Вы не авторизованы!'], JSON_UNESCAPED_UNICODE);
    exit();
}

// Подключение к базе данных PostgreSQL
$db_connection = pg_connect("host=pg3.sweb.ru dbname=saviorcheg user=saviorcheg password=Ss859756211");
if (!$db_connection) {
    die("Failed to connect to PostgreSQL");
}

$token = $_GET['token'];
$user_id = $_COOKIE['user_id'];
$query = "SELECT token FROM users WHERE id = $1";
$result = pg_query_params($db_connection, $query, array($user_id));
$user_data = pg_fetch_assoc($result);

if ((!isset($_COOKIE['user_id'])) or ($user_data['token'] != $token)) {
    echo json_encode(['error' => 'Вы не авторизованы или токен запроса не совпадает с вашим!'], JSON_UNESCAPED_UNICODE);
    exit();
}

// Обработчик GET-запроса для получения погодных данных с учетом параметра source
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получение параметра source из URL
    $source = isset($_GET['source']) ? filter_var($_GET['source'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

    // Проверка наличия параметра source
    if (!$source) {
        echo json_encode(['error' => 'Источник не указан'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Подготовленный запрос для выборки данных в зависимости от параметра source
    if ($source == 'all') {
        $query = "
            SELECT 
                city, 
                weather_date,
                temp, 
                feels_like, 
                condition, 
                wind_speed, 
                wind_dir, 
                pressure_pa, 
                humidity,
                source 
            FROM weather
            ORDER BY weather_date ASC
        ";
        $result = pg_query($db_connection, $query);
    } else {
        $query = "
            SELECT 
                city, 
                weather_date,
                temp, 
                feels_like, 
                condition, 
                wind_speed, 
                wind_dir, 
                pressure_pa, 
                humidity,
                source 
            FROM weather
            WHERE source = $1
            ORDER BY weather_date ASC
        ";
        $result = pg_query_params($db_connection, $query, array($source));
    }

    // Проверка успешности выполнения запроса
    if ($result) {
        $weather_data = pg_fetch_all($result);
        echo json_encode($weather_data, JSON_PRETTY_PRINT); // JSON_PRETTY_PRINT для читаемого форматирования
    } else {
        echo json_encode(['error' => 'Failed to retrieve weather data']);
    }
}

// Закрываем соединение с базой данных
pg_close($db_connection);
?>
