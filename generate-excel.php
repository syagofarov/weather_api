<?php
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="weather_data.xlsx"');
header('Cache-Control: max-age=0');


require 'vendor/autoload.php'; // Путь к автозагрузчику Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Подключение к базе данных PostgreSQL
$db_connection = pg_connect("host=pg3.sweb.ru dbname=saviorcheg user=saviorcheg password=Ss859756211");
if (!$db_connection) {
  die("Failed to connect to PostgreSQL");
}

// Подготовленный запрос для выборки данных из базы данных за последний месяц
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
  WHERE weather_date >= DATE_TRUNC('month', CURRENT_DATE - INTERVAL '1 month')
  ORDER BY weather_date ASC
";
$result = pg_query($db_connection, $query);

// Создаем новый объект Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Добавляем заголовки
$sheet->setCellValue('A1', 'Город');
$sheet->setCellValue('B1', 'Дата погоды');
$sheet->setCellValue('C1', 'Температура');
$sheet->setCellValue('D1', 'Ощущается как');
$sheet->setCellValue('E1', 'Условия');
$sheet->setCellValue('F1', 'Скорость ветра');
$sheet->setCellValue('G1', 'Направление ветра');
$sheet->setCellValue('H1', 'Давление');
$sheet->setCellValue('I1', 'Влажность');
$sheet->setCellValue('J1', 'Источник');

// Заполняем таблицу данными
$row = 2;
while ($weatherEntry = pg_fetch_assoc($result)) {
  $sheet->setCellValue('A' . $row, $weatherEntry['city']);
  $sheet->setCellValue('B' . $row, $weatherEntry['weather_date']);
  $sheet->setCellValue('C' . $row, $weatherEntry['temp']);
  $sheet->setCellValue('D' . $row, $weatherEntry['feels_like']);
  $sheet->setCellValue('E' . $row, $weatherEntry['condition']);
  $sheet->setCellValue('F' . $row, $weatherEntry['wind_speed']);
  $sheet->setCellValue('G' . $row, $weatherEntry['wind_dir']);
  $sheet->setCellValue('H' . $row, $weatherEntry['pressure_pa']);
  $sheet->setCellValue('I' . $row, $weatherEntry['humidity']);
  $sheet->setCellValue('J' . $row, $weatherEntry['source']);

  $row++;
}

// Создаем объект для записи в файл
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Закрываем соединение с базой данных
pg_close($db_connection);
?>