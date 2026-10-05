<?php

namespace App\Controllers;

use App\Models\Post;

class PostController
{
    public function __construct(
        private \Smarty $smarty,
        private Post $posts
    ) {}

    public function show(int $id): void
    {
        $post = $this->posts->find($id);
        if (!$post) {
            http_response_code(404);
            exit('Post not found');
        }

        $this->posts->incrementViews($id);
        $post['views']++;

        $this->smarty->assign([
            'post'      => $post,
            'categories'=> $this->posts->categories($id),
            'similar'   => $this->posts->similar($id, 3),
        ]);
        $this->smarty->display('post.tpl');
    }
}