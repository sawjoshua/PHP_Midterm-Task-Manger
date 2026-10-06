<?php
// Configure these with DB_HOST, DB_NAME, DB_USER, and DB_PASSWORD
// in the environment used to run PHP.
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'task_manager';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD');

if ($password === false) {
    $password = 'Root@123';
}

try {

    // Create PDO connection
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Enable PDO error mode
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e) {

    die("Connection Failed : " . $e->getMessage());

}
?>
