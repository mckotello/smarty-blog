<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'mysql',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'blog',
        'user' => getenv('DB_USER') ?: 'blog',
        'pass' => getenv('DB_PASS') ?: 'blog',
    ],
    'smarty' => [
        'template_dir' => __DIR__ . '/../templates',
        'compile_dir'  => __DIR__ . '/../var/compile',
        'cache_dir'    => __DIR__ . '/../var/cache',
    ],
    'pagination' => [
        'per_page' => 5,
    ],
];