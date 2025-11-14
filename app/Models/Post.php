<?php
namespace App\Models;

use PDO;
use PDOException;

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

    public static function getLikesCount(int $postId): int {
        $stmt = self::connect()->prepare('SELECT COUNT(*) as count FROM likes WHERE post_id = ?');
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }

    public static function isLikedByUser(int $postId, int $userId): bool {
        $stmt = self::connect()->prepare('SELECT id FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->execute([$postId, $userId]);
        return (bool)$stmt->fetch();
    }

    public static function addLike(int $postId, int $userId): bool {
        try {
            $stmt = self::connect()->prepare('INSERT INTO likes (post_id, user_id) VALUES (?, ?)');
            return $stmt->execute([$postId, $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function removeLike(int $postId, int $userId): bool {
        $stmt = self::connect()->prepare('DELETE FROM likes WHERE post_id = ? AND user_id = ?');
        return $stmt->execute([$postId, $userId]);
    }
    public static function getComments(int $postId): array {
        $stmt = self::connect()->prepare('
            SELECT c.*, u.name as user_name 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.post_id = ? 
            ORDER BY c.created_at ASC
        ');
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    public static function addComment(int $postId, int $userId, string $content): bool {
        $stmt = self::connect()->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)');
        return $stmt->execute([$postId, $userId, $content]);
    }
    public static function updatePost(int $postId, string $content): bool {
        $stmt = self::connect()->prepare('UPDATE posts SET content = ? WHERE id = ?');
        return $stmt->execute([$content, $postId]);
    }
    public static function getUserPostsWithDetails(int $userId): array {
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

}