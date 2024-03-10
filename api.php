<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale=1.0>
    <meta http-equiv="X-UA-Compitable" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>API</title>
  </head>
  <body>
    <?php require "blocks/header.php" ?>
    <?php  
      if (!isset($_COOKIE['user_id'])) {
        header('Location: /sign-in.php');
        exit();
      }
      $db_connection = pg_connect("host=pg3.sweb.ru dbname=saviorcheg user=saviorcheg password=Ss859756211");

      if (!$db_connection) {
        die("Failed to connect to PostgreSQL");
      }
      $user_id = $_COOKIE['user_id'];
      $query = "SELECT token FROM users WHERE id = '$user_id'";
      $result = pg_query($db_connection, $query);
      $user_data = pg_fetch_assoc($result);

      $token = $user_data['token'];

      pg_close($db_connection);
    ?>
    <div class="container flex-md-row mb-4 h-md-250">
      <h2 class="text-center">API погоды</h2>
      <ul>
        <li> <a class="h5 text-dark" href="/weather?source=yandex&token=<?php echo $token ?>" target="_blank">Яндекс погода</a> </li>        
        <li> <a class="h5 text-dark" href="/weather?source=openWeatherMap&token=<?php echo $token ?>" target="_blank">OpenWeatherMap погода</a> </li>
        <li> <a class="h5 text-dark" href="/weather?source=all&token=<?php echo $token ?>" target="_blank">Вся погода</a> </li>
      </ul>
      <h4>EXCEL-файлы</h4>
      <ul>
        <li> <a class="h5 text-dark" href="/generate-excel?token=<?php echo $token ?>" target="_blank"> Погодные данные за последний месяц </a> </li>
      </ul>
      <h4>Анализированные данные</h4>
      <ul>
        <li> <a class="h5 text-dark" href="/analyzed_weather?q=positive_temp&token=<?php echo $token ?>" target="_blank">Количество солнечных дней</a> </li>
      </ul>
    </div>

  </body>
</html>