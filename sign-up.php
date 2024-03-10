<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale=1.0>
    <meta http-equiv="X-UA-Compitable" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Регистрация</title>
</head>
<body>
  <?php require "blocks/header.php" ?>

  <form class="container" action ="check.php" method="post">
    <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>

    <input type="text" name="login" id="login" class="form-control" placeholder="Логин" autofocus=""> </br>
    
    <input type="text" id="name" name="name" class="form-control" placeholder="Имя"> </br>  
    
    <input type="password" id="pass" name="pass" class="form-control" placeholder="Пароль"> </br>
    
    <button class="btn btn-lg btn-block btn-primary "  type="submit">Зарегистрироваться</button>
  </form>

</body>
</html>