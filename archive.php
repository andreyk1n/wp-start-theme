<?php get_header(); ?>

<h1><?php the_archive_title(); ?></h1>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <article>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </article>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
