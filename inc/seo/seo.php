
<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO meta box.
 */
add_action('add_meta_boxes', function () {
    foreach (['page', 'post'] as $post_type) {
        add_meta_box(
            'theme_seo_settings',
            __('SEO налаштування', 'your-theme'),
            'theme_seo_render_meta_box',
            $post_type,
            'normal',
            'low'
        );
    }
});

/**
 * Render SEO fields.
 */
function theme_seo_render_meta_box($post)
{
    wp_nonce_field('theme_seo_save', 'theme_seo_nonce');

    $fields = [
        'title'       => get_post_meta($post->ID, '_theme_seo_title', true),
        'description' => get_post_meta($post->ID, '_theme_seo_description', true),
        'canonical'   => get_post_meta($post->ID, '_theme_seo_canonical', true),
        'og_image'    => get_post_meta($post->ID, '_theme_seo_og_image', true),
    ];

    $noindex = get_post_meta($post->ID, '_theme_seo_noindex', true);
    $nofollow = get_post_meta($post->ID, '_theme_seo_nofollow', true);
    ?>

    <p>
        <label for="theme_seo_title"><strong>SEO Title</strong></label>
        <input
            type="text"
            id="theme_seo_title"
            name="theme_seo_title"
            value="<?php echo esc_attr($fields['title']); ?>"
            class="widefat"
            placeholder="<?php echo esc_attr(get_the_title($post)); ?>"
        >
    </p>

    <p>
        <label for="theme_seo_description"><strong>Meta Description</strong></label>
        <textarea
            id="theme_seo_description"
            name="theme_seo_description"
            rows="3"
            class="widefat"
            maxlength="320"
        ><?php echo esc_textarea($fields['description']); ?></textarea>
    </p>

    <p>
        <label for="theme_seo_canonical"><strong>Canonical URL</strong></label>
        <input
            type="url"
            id="theme_seo_canonical"
            name="theme_seo_canonical"
            value="<?php echo esc_attr($fields['canonical']); ?>"
            class="widefat"
            placeholder="<?php echo esc_url(get_permalink($post)); ?>"
        >
    </p>

    <p>
        <label for="theme_seo_og_image"><strong>Open Graph Image URL</strong></label>
        <input
            type="url"
            id="theme_seo_og_image"
            name="theme_seo_og_image"
            value="<?php echo esc_attr($fields['og_image']); ?>"
            class="widefat"
            placeholder="https://example.com/image.jpg"
        >
    </p>

    <p>
        <label>
            <input
                type="checkbox"
                name="theme_seo_noindex"
                value="1"
                <?php checked($noindex, '1'); ?>
            >
            Заборонити індексацію сторінки (noindex)
        </label>
    </p>

    <p>
        <label>
            <input
                type="checkbox"
                name="theme_seo_nofollow"
                value="1"
                <?php checked($nofollow, '1'); ?>
            >
            Не передавати вагу посиланням (nofollow)
        </label>
    </p>

    <?php
}

/**
 * Save SEO fields.
 */
add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['theme_seo_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['theme_seo_nonce'])),
            'theme_seo_save'
        )
    ) {
        return;
    }

    if (
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        wp_is_post_revision($post_id) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $fields = [
        '_theme_seo_title' => [
            'input' => 'theme_seo_title',
            'sanitize' => 'sanitize_text_field',
        ],
        '_theme_seo_description' => [
            'input' => 'theme_seo_description',
            'sanitize' => 'sanitize_textarea_field',
        ],
        '_theme_seo_canonical' => [
            'input' => 'theme_seo_canonical',
            'sanitize' => 'esc_url_raw',
        ],
        '_theme_seo_og_image' => [
            'input' => 'theme_seo_og_image',
            'sanitize' => 'esc_url_raw',
        ],
    ];

    foreach ($fields as $meta_key => $field) {
        $input = $field['input'];

        if (!isset($_POST[$input])) {
            delete_post_meta($post_id, $meta_key);
            continue;
        }

        $value = call_user_func(
            $field['sanitize'],
            wp_unslash($_POST[$input])
        );

        if ($value === '') {
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $value);
        }
    }

    foreach ([
        'theme_seo_noindex' => '_theme_seo_noindex',
        'theme_seo_nofollow' => '_theme_seo_nofollow',
    ] as $input => $meta_key) {
        if (isset($_POST[$input]) && $_POST[$input] === '1') {
            update_post_meta($post_id, $meta_key, '1');
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
});

/**
 * Get SEO title or fall back to the regular page title.
 */
function theme_seo_get_title($post_id = 0)
{
    $post_id = $post_id ?: get_queried_object_id();
    $title = get_post_meta($post_id, '_theme_seo_title', true);

    return $title ?: get_the_title($post_id);
}

/**
 * Override document title when a custom SEO title exists.
 */
add_filter('pre_get_document_title', function ($title) {
    if (!is_singular()) {
        return $title;
    }

    $seo_title = get_post_meta(
        get_queried_object_id(),
        '_theme_seo_title',
        true
    );

    return $seo_title ?: $title;
});

/**
 * Use a custom canonical URL when provided.
 */
add_filter('get_canonical_url', function ($canonical_url, $post) {
    if (!$post instanceof WP_Post) {
        return $canonical_url;
    }

    $custom_url = get_post_meta(
        $post->ID,
        '_theme_seo_canonical',
        true
    );

    return $custom_url ? $custom_url : $canonical_url;
}, 10, 2);

/**
 * Robots directives.
 */
add_filter('wp_robots', function ($robots) {
    if (!is_singular()) {
        return $robots;
    }

    $post_id = get_queried_object_id();

    if (get_post_meta($post_id, '_theme_seo_noindex', true) === '1') {
        unset($robots['index']);
        $robots['noindex'] = true;
    }

    if (get_post_meta($post_id, '_theme_seo_nofollow', true) === '1') {
        unset($robots['follow']);
        $robots['nofollow'] = true;
    }

    return $robots;
});

/**
 * Open Graph metadata.
 */
add_action('wp_head', function () {
    if (!is_singular()) {
        return;
    }

    $post_id = get_queried_object_id();
    $post = get_post($post_id);

    if (!$post) {
        return;
    }

    $title = theme_seo_get_title($post_id);

    $description = get_post_meta(
        $post_id,
        '_theme_seo_description',
        true
    );

    if (!$description) {
        $description = has_excerpt($post_id)
            ? get_the_excerpt($post_id)
            : '';
    }

    $image = get_post_meta(
        $post_id,
        '_theme_seo_og_image',
        true
    );

    $url = wp_get_canonical_url($post_id) ?: get_permalink($post_id);

    if (!$image && has_post_thumbnail($post_id)) {
        $image = get_the_post_thumbnail_url($post_id, 'full');
    }

    $description = trim(
        preg_replace('/\s+/', ' ', wp_strip_all_tags($description))
    );
    ?>

    <?php if ($description !== '') : ?>
        <meta
            name="description"
            content="<?php echo esc_attr($description); ?>"
        >
    <?php endif; ?>

    <meta property="og:type" content="<?php echo esc_attr(get_post_type($post_id) === 'post' ? 'article' : 'website'); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">

    <?php if ($description !== '') : ?>
        <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>

    <?php if ($image) : ?>
        <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>

    <?php
}, 5);