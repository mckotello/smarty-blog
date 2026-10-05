<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;

class HomeController
{
    public function __construct(
        private \Smarty $smarty,
        private Category $categories,
        private Post $posts
    ) {}

    public function index(): void
    {
        $categories = $this->categories->withPosts();

        $result = [];
        foreach ($categories as $category) {
            $category['posts'] = $this->posts->latestByCategory((int)$category['id'], 3);
            $result[] = $category;
        }

        $this->smarty->assign('categories', $result);
        $this->smarty->display('home.tpl');
    }
}