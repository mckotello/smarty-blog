{extends file="layout.tpl"}
{block name=title}{$post.title}{/block}

{block name=content}
    <a href="{$base_url}" class="back">← На главную</a>

    <article class="post-full">
        <h1>{$post.title}</h1>

        <div class="meta">
            {$post.published_at|date_format:"d.m.Y H:i"} · 👁 {$post.views} просмотров
        </div>

        <div class="categories">
            Категории:
            {foreach $categories as $c}
                <a href="{$base_url}category/{$c.id}">{$c.name}</a>{if !$c@last}, {/if}
            {/foreach}
        </div>

        {if $post.image}
            <img class="cover" src="{$base_url}assets/images/{$post.image}" alt="{$post.title}">
        {/if}

        <p class="lead">{$post.description}</p>
        <div class="content">{$post.content|nl2br}</div>
    </article>

    {if $similar}
        <section class="similar">
            <h2>Похожие статьи</h2>
            <div class="posts-grid">
                {foreach $similar as $s}
                    <article class="post-card">
                        {if $s.image}
                            <img src="{$base_url}assets/images/{$s.image}" alt="{$s.title}">
                        {/if}
                        <h3><a href="{$base_url}post/{$s.id}">{$s.title}</a></h3>
                        <p>{$s.description|truncate:100}</p>
                    </article>
                {/foreach}
            </div>
        </section>
    {/if}
{/block}