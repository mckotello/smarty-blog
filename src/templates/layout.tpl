<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{block name=title}Блог{/block}</title>
    <link rel="stylesheet" href="{$base_url}assets/css/style.css">
</head>
<body>
<header>
    <a href="{$base_url}" class="logo">📝 Мой Блог</a>
</header>
<main>
    {block name=content}{/block}
</main>
<footer>© {$smarty.now|date_format:"%Y"} Блог на PHP + Smarty</footer>
</body>
</html>