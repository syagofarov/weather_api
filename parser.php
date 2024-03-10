<?php
  include 'functions.php';  

  $yandexObj = new stdClass();
  $openWeatherMapObj = new stdClass();

  // Яндекс Погода
  $access_key = '6a3739bc-0061-465e-9732-f35684626d94';
  $city = 'Пермь';

  $opts = array(
    'http' => array(
      'method' => 'GET',
      'header' => 'X-Yandex-Weather-Key: ' . $access_key
    )
  );

  $context = stream_context_create($opts);
  $file = file_get_contents("https://api.weather.yandex.ru/v2/fact?city=$city", false, $context);
  $weather_data = json_decode($file, true);

  $yandexObj->temperature = $weather_data['temp'];
  $yandexObj->feels_like = $weather_data['feels_like'];
  $yandexObj->condition = $weather_data['condition'];
  $yandexObj->wind_speed = $weather_data['wind_speed'];
  $yandexObj->wind_direction = $weather_data['wind_dir'];
  $yandexObj->pressure = $weather_data['pressure_pa'];
  $yandexObj->humidity = $weather_data['humidity'];

  // OpenWeatherMap
  $api_key = 'e8d649dc5cd604269e2765b94cd2a124';
  $city = 'Perm';
  $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$api_key";
  $file = file_get_contents($url);
  $weather_data = json_decode($file, true);

  $openWeatherMapObj->temperature = $weather_data['main']['temp'] - 273.15;
  $openWeatherMapObj->feels_like = $weather_data['main']['feels_like'] - 273.15;
  $openWeatherMapObj->condition = $weather_data['weather'][0]['description'];
  $openWeatherMapObj->wind_speed = $weather_data['wind']['speed'];
  $openWeatherMapObj->wind_direction = getWindDirection($weather_data['wind']['deg']);
  $openWeatherMapObj->pressure = $weather_data['main']['pressure'];
  $openWeatherMapObj->humidity = $weather_data['main']['humidity'];

  insertData($yandexObj, 'yandex');
  insertData($openWeatherMapObj, 'openWeatherMap');