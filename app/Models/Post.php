<?php
namespace App\Models;

use PDO;

class Post {
    protected static function connect(): PDO {
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

    public static function create(int $userId, string $content, ?string $image = null): int {
        $stmt = self::connect()->prepare('INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $content, $image]);
        return (int)self::connect()->lastInsertId();
    }

    public static function getAllWithUsers(): array {
        $stmt = self::connect()->prepare('
            SELECT p.*, u.name as user_name 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findByUserId(int $userId): array {
        $stmt = self::connect()->prepare('
            SELECT p.*, u.name as user_name 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.user_id = ? 
            ORDER BY p.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    public static function getById($id)
    {
        $stmt = self::connect()->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $stmt = self::connect()->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}