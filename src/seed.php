<?php

require __DIR__ . '/vendor/autoload.php';

use App\Database;

$config = require __DIR__ . '/config/config.php';
$db = Database::getConnection($config['db']);

echo "Очистка таблиц...\n";
$db->exec('SET FOREIGN_KEY_CHECKS=0');
$db->exec('TRUNCATE TABLE post_category');
$db->exec('TRUNCATE TABLE posts');
$db->exec('TRUNCATE TABLE categories');
$db->exec('SET FOREIGN_KEY_CHECKS=1');

// ---------- Категории ----------
$categories = [
    ['Технологии',   'Всё о современных технологиях и разработке'],
    ['Путешествия',  'Истории о поездках и интересных местах'],
    ['Кулинария',    'Рецепты и советы по готовке'],
    ['Спорт',        'Новости спорта и здоровый образ жизни'],
];

$catStmt = $db->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
$catIds = [];
foreach ($categories as $c) {
    $catStmt->execute($c);
    $catIds[] = (int)$db->lastInsertId();
}
echo "Добавлено категорий: " . count($catIds) . "\n";

// ---------- Статьи ----------
$posts = [
    [
        'title' => 'Введение в PHP 8.2',
        'description' => 'Обзор новых возможностей PHP 8.2: readonly-классы, DNF-типы и другие улучшения.',
        'content' => "PHP 8.2 принёс множество полезных изменений.\n\nГлавные нововведения:\n- readonly классы\n- типы DNF\n- удаление динамических свойств\n- улучшения в случайных числах\n\nЭто делает язык строже и удобнее для крупных проектов.",
        'image' => 'php.jpg',
        'views' => 150,
        'published_at' => '2024-11-01 10:00:00',
        'categories' => [0],
    ],
    [
        'title' => 'Docker для начинающих',
        'description' => 'Что такое контейнеры и зачем они нужны разработчику.',
        'content' => "Docker позволяет упаковать приложение со всеми зависимостями в контейнер.\n\nОсновные понятия:\n- image\n- container\n- volume\n- network\n\nЭто упрощает деплой и делает окружение воспроизводимым.",
        'image' => 'docker.jpg',
        'views' => 320,
        'published_at' => '2024-11-05 12:00:00',
        'categories' => [0],
    ],
    [
        'title' => '10 мест в Грузии',
        'description' => 'Куда поехать в Грузии, кроме Тбилиси.',
        'content' => "Грузия — удивительная страна.\n\nОбязательно посетите:\n1. Казбеги\n2. Сванетию\n3. Батуми\n4. Сигнахи\n5. Вардзию\n\nИ это только начало!",
        'image' => 'georgia.jpg',
        'views' => 210,
        'published_at' => '2024-11-10 09:30:00',
        'categories' => [1],
    ],
    [
        'title' => 'Паста карбонара: классический рецепт',
        'description' => 'Настоящая итальянская карбонара без сливок.',
        'content' => "Ингредиенты:\n- спагетти 200г\n- гуанчале 100г\n- яйца 2 шт + 1 желток\n- пекорино романо 50г\n- чёрный перец\n\nГотовим: обжариваем гуанчале, смешиваем яйца с сыром, соединяем с горячей пастой. Сливки не нужны!",
        'image' => 'pasta.jpg',
        'views' => 540,
        'published_at' => '2024-11-12 18:00:00',
        'categories' => [2],
    ],
    [
        'title' => 'Утренняя зарядка за 10 минут',
        'description' => 'Комплекс упражнений для бодрого утра.',
        'content' => "Несколько простых упражнений:\n1. Наклоны\n2. Приседания\n3. Планка\n4. Растяжка\n\n10 минут в день — и вы почувствуете разницу.",
        'image' => 'sport.jpg',
        'views' => 90,
        'published_at' => '2024-11-14 07:00:00',
        'categories' => [3],
    ],
    [
        'title' => 'Smarty: шаблонизатор для PHP',
        'description' => 'Как использовать Smarty в своих проектах.',
        'content' => "Smarty отделяет логику от представления.\n\nПример:\n{\$var}\n{foreach ...}...{/foreach}\n\nЭто делает код чище.",
        'image' => 'smarty.jpg',
        'views' => 75,
        'published_at' => '2024-11-15 11:00:00',
        'categories' => [0],
    ],
];

$postStmt = $db->prepare(
    'INSERT INTO posts (title, description, content, image, views, published_at) 
     VALUES (?, ?, ?, ?, ?, ?)'
);
$linkStmt = $db->prepare('INSERT INTO post_category (post_id, category_id) VALUES (?, ?)');

foreach ($posts as $p) {
    $postStmt->execute([
        $p['title'], $p['description'], $p['content'],
        $p['image'], $p['views'], $p['published_at'],
    ]);
    $postId = (int)$db->lastInsertId();
    foreach ($p['categories'] as $ci) {
        $linkStmt->execute([$postId, $catIds[$ci]]);
    }
}
echo "Добавлено статей: " . count($posts) . "\n";
echo "✅ Готово!\n";