<?php

//SET to True if you want to migrate the table.
$migrateTable = false;

$host = "localhost";
$port = 3306; // default is 3306
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "rf_db"; // DB Name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($migrateTable) && $migrateTable) {
    $table = [];
    $table[] = " CREATE TABLE IF NOT EXISTS `users` (
        `user_id` int NOT NULL AUTO_INCREMENT,
        `user_name` varchar(255) NOT NULL,
        `user_email` varchar(255) NOT NULL,
        `user_password` varchar(255) NOT NULL,
        `user_number` bigint unsigned NOT NULL,
        `user_location` varchar(255) DEFAULT NULL,
        `user_status` enum('active','inActive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'inActive',
        `user_type` enum('admin','user') NOT NULL DEFAULT 'user',
        PRIMARY KEY (`user_id`)
      )";

    $table[] = "CREATE TABLE IF NOT EXISTS `rooms` (
        `room_id` int NOT NULL AUTO_INCREMENT,
        `room_name` varchar(255) NOT NULL,
        `room_location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
        `room_price` int unsigned DEFAULT NULL,
        `room_type` varchar(255) DEFAULT NULL,
        `room_status` enum('active','inActive') NOT NULL,
        `room_description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
        `room_image` varchar(255) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`room_id`)
      ) ";

    $table[] = "CREATE TABLE IF NOT EXISTS `bookings` (
        `booking_id` int NOT NULL AUTO_INCREMENT,
        `user_id` int DEFAULT NULL,
        `room_id` int DEFAULT NULL,
        `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `description` varchar(255) DEFAULT NULL,
        `status` enum('pending','confirmed','canceled') DEFAULT 'pending',
        `is_active` tinyint DEFAULT '1',
        PRIMARY KEY (`booking_id`),
        KEY `user_id` (`user_id`),
        KEY `room_id` (`room_id`),
        CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
        CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`)
      )";

    foreach ($table as $key => $migrateTableToDB) {
        $conn->query($migrateTableToDB);
        if ($conn) {
            echo "TABLE " . ++$key . " Migrated Successfully.<br><br>";
        }
    }
}

return $conn;

?>
