<?php

namespace App\Models;

use PDO;

class Category
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Категории, в которых есть статьи
     */
    public function withPosts(): array
    {
        $sql = 'SELECT DISTINCT c.*
                FROM categories c
                INNER JOIN post_category pc ON pc.category_id = c.id
                INNER JOIN posts p ON p.id = pc.post_id
                ORDER BY c.name';
        return $this->db->query($sql)->fetchAll();
    }
}