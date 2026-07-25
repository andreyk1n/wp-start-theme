<?php if (have_rows('flexible_content')) : ?>
    <style>
        <?php
        $loaded = [];
        while (have_rows('flexible_content')) :
            the_row();
            $layout = get_row_layout();
            if (!isset($loaded[$layout])) {
                $css = get_stylesheet_directory() . "/css/components/{$layout}.css";
                if (file_exists($css)) {
                    include $css;
                }
                $loaded[$layout] = true;
            }
        endwhile;
        reset_rows();
        ?>
    </style>

    <?php while (have_rows('flexible_content')) : the_row(); ?>
        <?php get_template_part("template-parts/components/" . get_row_layout()); ?>
    <?php endwhile; ?>
<?php endif; ?>