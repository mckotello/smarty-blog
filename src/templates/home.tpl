{extends file="layout.tpl"}
{block name=title}Главная{/block}

{block name=content}
    <h1>Все категории</h1>

    {foreach $categories as $category}
        <section class="category-block">
            <div class="category-header">
                <h2>{$category.name}</h2>
                <p>{$category.description}</p>
                <a class="btn" href="{$base_url}category/{$category.id}">Все статьи →</a>
            </div>

            <div class="posts-grid">
                {foreach $category.posts as $post}
                    <article class="post-card">
                        {if $post.image}
                            <img src="{$base_url}assets/images/{$post.image}" alt="{$post.title}">
                        {/if}
                        <h3><a href="{$base_url}post/{$post.id}">{$post.title}</a></h3>
                        <p>{$post.description|truncate:120}</p>
                        <small>
                            {$post.published_at|date_format:"d.m.Y"} ·
                            👁 {$post.views}
                        </small>
                    </article>
                {/foreach}
            </div>
        </section>
        {foreachelse}
        <p>Пока нет ни одной статьи.</p>
    {/foreach}
{/block}