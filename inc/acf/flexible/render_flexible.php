<?php

function theme_render_flexible(): void
{
    ?>
    <style>
        <?php
        include get_stylesheet_directory() . '/css/pages/index.css';
        ?>    <?php
            $loaded_styles = [];
            if (have_rows('flexible_content')):
                while (have_rows('flexible_content')):
                    the_row();
                    $layout = get_row_layout();
                    if (!in_array($layout, $loaded_styles, true)) {
                        $file = get_stylesheet_directory() . "/css/components/{$layout}.css";
                        if (file_exists($file)) {
                            include $file;
                            $loaded_styles[] = $layout;
                        }
                    }
                endwhile;
            endif;
            ?>
    </style>

    <?php
    if (have_rows('flexible_content')):
        while (have_rows('flexible_content')):
            the_row();
            $layout = get_row_layout();
            get_template_part("template-parts/components/{$layout}");
        endwhile;
    endif;
?>
<?php
}
