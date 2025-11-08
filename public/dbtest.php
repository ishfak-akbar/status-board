<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=authboard', 'root', '');
    echo "Connected!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}