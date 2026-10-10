
<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO meta box for pages and posts.
 */
add_action('add_meta_boxes', function () {
    foreach (['page', 'post'] as $post_type) {
        add_meta_box(
            'theme_seo_settings',
            __('SEO налаштування', 'starter-theme'),
            'theme_seo_render_meta_box',
            $post_type,
            'normal',
            'low'
        );
    }
});

/**
 * Enqueue WordPress media library and SEO image picker.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }

    $screen = get_current_screen();

    if (!$screen || !in_array($screen->post_type, ['page', 'post'], true)) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('jquery');

    $script = <<<'JS'
jQuery(function ($) {
    let mediaFrame;

    $(document).on('click', '#theme-seo-select-image', function (e) {
        e.preventDefault();

        if (mediaFrame) {
            mediaFrame.open();
            return;
        }

        mediaFrame = wp.media({
            title: 'Вибрати SEO-зображення',
            button: {
                text: 'Використати це зображення'
            },
            library: {
                type: 'image'
            },
            multiple: false
        });

        mediaFrame.on('select', function () {
            const attachment = mediaFrame
                .state()
                .get('selection')
                .first()
                .toJSON();

            $('#theme_seo_og_image_id').val(attachment.id);
            $('#theme-seo-image-preview')
                .attr('src', attachment.url)
                .show();

            $('#theme-seo-remove-image').show();
        });

        mediaFrame.open();
    });

    $(document).on('click', '#theme-seo-remove-image', function (e) {
        e.preventDefault();

        $('#theme_seo_og_image_id').val('');
        $('#theme-seo-image-preview').attr('src', '').hide();
        $(this).hide();
    });
});
JS;

    wp_add_inline_script('jquery', $script);
});

/**
 * Render SEO fields.
 */
function theme_seo_render_meta_box($post)
{
    wp_nonce_field('theme_seo_save', 'theme_seo_nonce');

    $title = get_post_meta(
        $post->ID,
        '_theme_seo_title',
        true
    );

    $description = get_post_meta(
        $post->ID,
        '_theme_seo_description',
        true
    );

    $canonical = get_post_meta(
        $post->ID,
        '_theme_seo_canonical',
        true
    );

    $image_id = absint(get_post_meta(
        $post->ID,
        '_theme_seo_og_image_id',
        true
    ));

    // Support the image URL saved by the previous version.
    $legacy_image = get_post_meta(
        $post->ID,
        '_theme_seo_og_image',
        true
    );

    $image_url = $image_id
        ? wp_get_attachment_image_url($image_id, 'full')
        : $legacy_image;

    $noindex = get_post_meta(
        $post->ID,
        '_theme_seo_noindex',
        true
    );

    $nofollow = get_post_meta(
        $post->ID,
        '_theme_seo_nofollow',
        true
    );
    ?>

    <div class="theme-seo-fields">

        <p>
            <label for="theme_seo_title">
                <strong>SEO Title</strong>
            </label>

            <input
                type="text"
                id="theme_seo_title"
                name="theme_seo_title"
                value="<?php echo esc_attr($title); ?>"
                class="widefat"
                placeholder="<?php echo esc_attr(get_the_title($post)); ?>"
            >

            <small>Заголовок сторінки для пошукової видачі.</small>
        </p>

        <p>
            <label for="theme_seo_description">
                <strong>Meta Description</strong>
            </label>

            <textarea
                id="theme_seo_description"
                name="theme_seo_description"
                rows="3"
                maxlength="320"
                class="widefat"
            ><?php echo esc_textarea($description); ?></textarea>

            <small>Короткий опис сторінки для пошукових систем.</small>
        </p>

        <p>
            <label for="theme_seo_canonical">
                <strong>Canonical URL</strong>
            </label>

            <input
                type="url"
                id="theme_seo_canonical"
                name="theme_seo_canonical"
                value="<?php echo esc_attr($canonical); ?>"
                class="widefat"
                placeholder="<?php echo esc_url(get_permalink($post)); ?>"
            >

            <small>Залиш порожнім, щоб використовувати стандартну адресу.</small>
        </p>

        <hr>

        <div class="theme-seo-image-field">
            <p>
                <strong>Open Graph Image</strong>
            </p>

            <p>
                <img
                    id="theme-seo-image-preview"
                    src="<?php echo esc_url($image_url ?: ''); ?>"
                    alt=""
                    style="max-width: 400px; width: 100%; height: auto; <?php echo $image_url ? '' : 'display:none;'; ?>"
                >
            </p>

            <input
                type="hidden"
                id="theme_seo_og_image_id"
                name="theme_seo_og_image_id"
                value="<?php echo esc_attr($image_id); ?>"
            >

            <button
                type="button"
                class="button"
                id="theme-seo-select-image"
            >
                Вибрати зображення
            </button>

            <button
                type="button"
                class="button"
                id="theme-seo-remove-image"
                style="<?php echo $image_url ? '' : 'display:none;'; ?>"
            >
                Видалити зображення
            </button>

            <p>
                <small>
                    Зображення для прев'ю посилання в соцмережах і месенджерах.
                    Рекомендований розмір — 1200 × 630 px.
                </small>
            </p>
        </div>

        <hr>

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
                Заборонити перехід за посиланнями для пошукових роботів (nofollow)
            </label>
        </p>

    </div>

    <?php
}

/**
 * Save SEO fields.
 */
add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['theme_seo_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['theme_seo_nonce'])
            ),
            'theme_seo_save'
        )
    ) {
        return;
    }

    if (
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        wp_is_post_revision($post_id) ||
        wp_is_post_autosave($post_id) ||
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

    // Save the selected image attachment ID.
    $image_id = isset($_POST['theme_seo_og_image_id'])
        ? absint($_POST['theme_seo_og_image_id'])
        : 0;

    if (
        $image_id &&
        wp_attachment_is_image($image_id)
    ) {
        update_post_meta(
            $post_id,
            '_theme_seo_og_image_id',
            $image_id
        );

        // Remove the old URL field after selecting a new image.
        delete_post_meta($post_id, '_theme_seo_og_image');
    } else {
        delete_post_meta($post_id, '_theme_seo_og_image_id');

        // The legacy URL, if any, remains available as a fallback.
    }

    // Save robots settings.
    foreach ([
        'theme_seo_noindex' => '_theme_seo_noindex',
        'theme_seo_nofollow' => '_theme_seo_nofollow',
    ] as $input => $meta_key) {
        if (
            isset($_POST[$input]) &&
            wp_unslash($_POST[$input]) === '1'
        ) {
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

    $title = get_post_meta(
        $post_id,
        '_theme_seo_title',
        true
    );

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

    return $custom_url ?: $canonical_url;
}, 10, 2);

/**
 * Robots directives.
 */
add_filter('wp_robots', function ($robots) {
    if (!is_singular()) {
        return $robots;
    }

    $post_id = get_queried_object_id();

    if (
        get_post_meta($post_id, '_theme_seo_noindex', true) === '1'
    ) {
        unset($robots['index']);
        $robots['noindex'] = true;
    }

    if (
        get_post_meta($post_id, '_theme_seo_nofollow', true) === '1'
    ) {
        unset($robots['follow']);
        $robots['nofollow'] = true;
    }

    return $robots;
});

/**
 * Open Graph and meta description.
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
        $description = get_the_excerpt($post_id);
    }

    // Use the selected image, legacy URL, or featured image.
    $image_id = absint(get_post_meta(
        $post_id,
        '_theme_seo_og_image_id',
        true
    ));

    $image = $image_id
        ? wp_get_attachment_image_url($image_id, 'full')
        : '';

    if (!$image) {
        $image = get_post_meta(
            $post_id,
            '_theme_seo_og_image',
            true
        );
    }

    if (!$image && has_post_thumbnail($post_id)) {
        $image = get_the_post_thumbnail_url($post_id, 'full');
    }

    $url = wp_get_canonical_url($post_id);

    if (!$url) {
        $url = get_permalink($post_id);
    }

    $description = trim(
        preg_replace(
            '/\s+/',
            ' ',
            wp_strip_all_tags($description)
        )
    );
    ?>

    <?php if ($description !== '') : ?>
        <meta
            name="description"
            content="<?php echo esc_attr($description); ?>"
        >
    <?php endif; ?>

    <meta
        property="og:type"
        content="<?php echo esc_attr(get_post_type($post_id) === 'post' ? 'article' : 'website'); ?>"
    >

    <meta
        property="og:title"
        content="<?php echo esc_attr($title); ?>"
    >

    <meta
        property="og:url"
        content="<?php echo esc_url($url); ?>"
    >

    <?php if ($description !== '') : ?>
        <meta
            property="og:description"
            content="<?php echo esc_attr($description); ?>"
        >
    <?php endif; ?>

    <?php if ($image) : ?>
        <meta
            property="og:image"
            content="<?php echo esc_url($image); ?>"
        >
    <?php endif; ?>

    <?php
}, 5);
