<?php

namespace App\Models;

use PDO;

class Post
{
    public function __construct(private PDO $db) {}

    /**
     * N последних постов в категории (по дате публикации)
     */
    public function latestByCategory(int $categoryId, int $limit = 3): array
    {
        $sql = 'SELECT p.*
                FROM posts p
                INNER JOIN post_category pc ON pc.post_id = p.id
                WHERE pc.category_id = :cid
                ORDER BY p.published_at DESC
                LIMIT ' . (int)$limit;
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $categoryId]);
        return $stmt->fetchAll();
    }

    /**
     * Посты категории с сортировкой и пагинацией
     */
    public function byCategory(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = $sort === 'views' ? 'p.views DESC' : 'p.published_at DESC';
        $sql = "SELECT p.*
                FROM posts p
                INNER JOIN post_category pc ON pc.post_id = p.id
                WHERE pc.category_id = :cid
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $sql = 'SELECT COUNT(*) FROM post_category WHERE category_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return (int)$stmt->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE posts SET views = views + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Категории поста
     */
    public function categories(int $postId): array
    {
        $sql = 'SELECT c.* FROM categories c
                INNER JOIN post_category pc ON pc.category_id = c.id
                WHERE pc.post_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    /**
     * Похожие статьи — те, что в тех же категориях
     */
    public function similar(int $postId, int $limit = 3): array
    {
        $sql = 'SELECT DISTINCT p.*
                FROM posts p
                INNER JOIN post_category pc ON pc.post_id = p.id
                WHERE p.id != :pid
                  AND pc.category_id IN (
                      SELECT category_id FROM post_category WHERE post_id = :pid
                  )
                ORDER BY p.published_at DESC
                LIMIT ' . (int)$limit;
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pid' => $postId]);
        return $stmt->fetchAll();
    }
}