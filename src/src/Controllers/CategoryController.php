<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;

class CategoryController
{
    public function __construct(
        private \Smarty $smarty,
        private Category $categories,
        private Post $posts,
        private int $perPage
    ) {}

    public function show(int $id): void
    {
        $category = $this->categories->find($id);
        if (!$category) {
            http_response_code(404);
            exit('Category not found');
        }

        $sort = $_GET['sort'] ?? 'date';
        if (!in_array($sort, ['date', 'views'], true)) {
            $sort = 'date';
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $total = $this->posts->countByCategory($id);
        $pages = max(1, (int)ceil($total / $this->perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $this->perPage;

        $posts = $this->posts->byCategory($id, $sort, $this->perPage, $offset);

        $this->smarty->assign([
            'category' => $category,
            'posts'    => $posts,
            'sort'     => $sort,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
        ]);
        $this->smarty->display('category.tpl');
    }
}