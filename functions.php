<?php 

require 'vendor/autoload.php';
use Ramsey\Uuid\Uuid;

// Функция получения направления ветра в зависимости от градусов
function getWindDirection($degrees) {
  $directions = array('n', 'ne', 'e', 'se', 's', 'sw', 'w', 'nw');
  $index = round($degrees / 45) % 8;
  return $directions[$index];
}

function insertData($data, $source) {
  // Подключение к базе данных
  $db_connection = pg_connect("host=pg3.sweb.ru dbname=saviorcheg user=saviorcheg password=Ss859756211");

  // Проверка на успешное подключение
  if (!$db_connection) {
      die("Failed to connect to PostgreSQL");
  }

  // Ваш SQL-запрос для вставки данных в таблицу вашей базы данных
  $query = "
    INSERT INTO weather (
      id, 
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
    ) 
    VALUES (
      '".Uuid::uuid4()."',
      'Perm',
      '".date('Y-m-d')."',
      '".$data->temperature."',
      '".$data->feels_like."',
      '".$data->condition."',
      '".$data->wind_speed."',
      '".$data->wind_direction."',
      '".$data->pressure."',
      '".$data->humidity."',
      '".$source."'
    )
  ";

  // Выполнение запроса
  pg_query($db_connection, $query);

  // Закрываем соединение с базой данных
  pg_close($db_connection);
}