<?php get_header(); ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <article>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
        </article>
    <?php endwhile; ?>
<?php else : ?>
    <p>Нічого не знайдено.</p>
<?php endif; ?>

<?php get_footer(); ?>
