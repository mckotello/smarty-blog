{extends file="layout.tpl"}
{block name=title}{$category.name}{/block}

{block name=content}
    <a href="{$base_url}" class="back">← Назад</a>
    <h1>{$category.name}</h1>
    <p class="description">{$category.description}</p>

    <div class="sort-bar">
        Сортировка:
        <a href="?sort=date" class="{if $sort=='date'}active{/if}">по дате</a> |
        <a href="?sort=views" class="{if $sort=='views'}active{/if}">по просмотрам</a>
    </div>

    <p class="total">Всего статей: {$total}</p>

    <div class="posts-list">
        {foreach $posts as $post}
            <article class="post-row">
                {if $post.image}
                    <img src="{$base_url}assets/images/{$post.image}" alt="{$post.title}">
                {/if}
                <div>
                    <h3><a href="{$base_url}post/{$post.id}">{$post.title}</a></h3>
                    <p>{$post.description|truncate:200}</p>
                    <small>
                        {$post.published_at|date_format:"d.m.Y"} ·
                        👁 {$post.views}
                    </small>
                </div>
            </article>
            {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {if $pages > 1}
        <nav class="pagination">
            {for $p=1 to $pages}
                {if $p == $page}
                    <span class="current">{$p}</span>
                {else}
                    <a href="?sort={$sort}&page={$p}">{$p}</a>
                {/if}
            {/for}
        </nav>
    {/if}
{/block}