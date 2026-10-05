<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Models\Category;
use App\Models\Post;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\PostController;

$config = require __DIR__ . '/../config/config.php';

// --- Smarty ---
$smarty = new Smarty();
$smarty->setTemplateDir($config['smarty']['template_dir']);
$smarty->setCompileDir($config['smarty']['compile_dir']);
$smarty->setCacheDir($config['smarty']['cache_dir']);
$smarty->caching = Smarty::CACHING_OFF;
$smarty->assign('base_url', '/');

// --- БД и модели ---
$db = Database::getConnection($config['db']);
$categoryModel = new Category($db);
$postModel = new Post($db);

// --- Простой роутер ---
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

try {
    if ($uri === '/') {
        (new HomeController($smarty, $categoryModel, $postModel))->index();

    } elseif (preg_match('#^/category/(\d+)$#', $uri, $m)) {
        (new CategoryController($smarty, $categoryModel, $postModel, $config['pagination']['per_page']))
            ->show((int)$m[1]);

    } elseif (preg_match('#^/post/(\d+)$#', $uri, $m)) {
        (new PostController($smarty, $postModel))->show((int)$m[1]);

    } else {
        http_response_code(404);
        echo '404 Not Found';
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}