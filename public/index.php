<?php

use Illuminate\Http\Request;

//$db = new PDO('mysql:host=db; dbname=badger_hotel', 'root', 'password');
//
//$query = $db->prepare('SELECT
//	types.id,
//	types.name,
//	bookings.id,
//	COUNT(bookings.id),
//	AVG(DATEDIFF(bookings.end, bookings.start)) as Days
//FROM types
//	LEFT JOIN rooms
//	ON types.id = rooms.type_id
//RIGHT JOIN bookings
//	ON rooms.id = bookings.room_id
//	GROUP BY types.id');
//$query->execute();
//
//echo '<pre>';
//var_dump($query->fetchAll());

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
