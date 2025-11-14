<?php
namespace App\Models;

use PDO;

class User {
    private static function connect(): PDO {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $db = getenv('DB_NAME') ?: 'authboard';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }

    public static function findByEmail(string $email): ?array {
        $stmt = self::connect()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $name, string $email, string $password): int {
        $stmt = self::connect()->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $password]);
        return (int)self::connect()->lastInsertId();
    }
    public static function search(string $query): array {
    $searchTerm = trim($query);
    
    if (empty($searchTerm)) {
        return [];
    }
    
    $stmt = self::connect()->prepare('
        SELECT id, name, email, created_at 
        FROM users 
        WHERE 
            name LIKE ? OR  -- Starts with search term
            name LIKE ? OR  -- First name match  
            name LIKE ? OR  -- Last name match
            email = ?       -- Exact email match
        ORDER BY name ASC
        LIMIT 20
    ');
    
    $startsWith = $searchTerm . '%';
    $firstName = $searchTerm . ' %';
    $lastName = '% ' . $searchTerm . '%';
    
    $stmt->execute([
        $startsWith,  // starts with
        $firstName,   // first name
        $lastName,    // last name  
        $searchTerm   // exact email
    ]);
    
    return $stmt->fetchAll();
}

    public static function findById(int $userId): ?array {
        $stmt = self::connect()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

}
