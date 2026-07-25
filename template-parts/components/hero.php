<?php
$title = get_sub_field('title');
$label = get_sub_field('label');
$text = get_sub_field('text');
$buttons = get_sub_field('buttons');
$image = get_sub_field('image');
?>

<section class="hero">
    <div class="hero__container">
        <div class="hero__content">
            <?php if ($label): ?>
                <span class="hero__label">
                    <?php echo esc_html($label); ?>
                </span>
            <?php endif; ?>
            <?php if ($title): ?>
                <h1 class="hero__title">
                    <?php echo esc_html($title); ?>
                </h1>
            <?php endif; ?>
            <?php if ($text): ?>
                <div class="hero__text">
                    <?php echo esc_html($text); ?>
                </div>
            <?php endif; ?>
            <?php if ($buttons): ?>
                <div class="hero__actions">
                    <?php foreach ($buttons as $button):
                        $button_text = $button['button_text'] ?? '';
                        $button_url = $button['button_url'] ?? '';
                        if (!$button_text || !$button_url) {
                            continue;
                        }
                        ?>
                        <a class="hero__button" href="<?php echo esc_url($button_url); ?>">
                            <?php echo esc_html($button_text); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="hero__media">
            <?php if (!empty($image)): ?>
                <img class="hero__image" src="<?php echo $image; ?>"
                    alt="<?php the_title(); ?>">
            <?php endif; ?>
        </div>
    </div>
</section>