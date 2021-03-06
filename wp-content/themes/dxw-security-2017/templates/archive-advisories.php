<section class="page-header-panel">
    <div class="row">
        <h1>Advisories</h1>
    </div>
</section>

<?php get_template_part('partials/advisories-search-form'); ?>

<div class="row">
    <section class="feed-container page-section">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <?php get_template_part('partials/article-list-item'); ?>
            <?php endwhile; ?>
        <?php endif; ?>
        <div class="pager">
            <?php get_template_part('partials/pager') ?>
        </div>
    </section>
    <?php if ( is_active_sidebar( 'sidebar-advisories' ) ) : ?>
        <aside class="sidebar page-section">
            <?php dynamic_sidebar( 'sidebar-advisories' ); ?>
        </aside>
    <?php endif; ?>
</div>

<?php get_template_part('partials/options-banner'); ?>
