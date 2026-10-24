
<?php
/**
 * Shared Schema.org helpers.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the canonical URL for the current request.
 *
 * For archives and singular pages, use the WordPress permalink.
 * A project may override this through the filter.
 *
 * @return string
 */
function theme_schema_get_current_url() {
    if ( is_front_page() ) {
        $url = home_url( '/' );
    } elseif ( is_singular() ) {
        $url = get_permalink( get_queried_object_id() );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $url = get_term_link( get_queried_object() );
    } elseif ( is_post_type_archive() ) {
        $url = get_post_type_archive_link(
            get_query_var( 'post_type' )
        );
    } elseif ( is_author() ) {
        $url = get_author_posts_url(
            (int) get_query_var( 'author' )
        );
    } elseif ( is_home() ) {
        $posts_page_id = (int) get_option( 'page_for_posts' );

        $url = $posts_page_id
            ? get_permalink( $posts_page_id )
            : home_url( '/' );
    } else {
        $url = home_url( '/' );
    }

    if ( is_wp_error( $url ) || ! is_string( $url ) ) {
        $url = home_url( '/' );
    }

    /**
     * Override with a project-specific canonical URL if needed.
     *
     * @param string $url Current URL.
     */
    $url = apply_filters( 'theme_schema_current_url', $url );

    return esc_url_raw( $url );
}

/**
 * Get the website entity ID.
 *
 * @return string
 */
function theme_schema_website_id() {
    return trailingslashit( home_url( '/' ) ) . '#website';
}

/**
 * Get the organization entity ID.
 *
 * @return string
 */
function theme_schema_organization_id() {
    return trailingslashit( home_url( '/' ) ) . '#organization';
}

/**
 * Get a stable ID for a page.
 *
 * @return string
 */
function theme_schema_webpage_id() {
    return theme_schema_get_current_url() . '#webpage';
}

/**
 * Read a value from ACF if available, otherwise use post meta.
 *
 * @param string   $key     Field name.
 * @param int|null $post_id Post ID.
 * @return mixed
 */
function theme_schema_get_field( $key, $post_id = null ) {
    if ( ! is_string( $key ) || '' === $key ) {
        return null;
    }

    if ( null === $post_id ) {
        $post_id = get_queried_object_id();
    }

    if (
        function_exists( 'get_field' ) &&
        $post_id
    ) {
        $value = get_field( $key, $post_id );

        if ( null !== $value && false !== $value && '' !== $value ) {
            return $value;
        }
    }

    if ( $post_id ) {
        return get_post_meta( $post_id, $key, true );
    }

    return null;
}

/**
 * Normalize a scalar or text value.
 *
 * @param mixed $value Input value.
 * @return string
 */
function theme_schema_text( $value ) {
    if ( ! is_scalar( $value ) ) {
        return '';
    }

    return trim(
        sanitize_text_field( (string) $value )
    );
}

/**
 * Validate an absolute HTTP(S) URL.
 *
 * @param mixed $url URL candidate.
 * @return string
 */
function theme_schema_url( $url ) {
    if ( ! is_string( $url ) || '' === trim( $url ) ) {
        return '';
    }

    $url = esc_url_raw( trim( $url ), array( 'http', 'https' ) );

    if ( ! $url || ! wp_http_validate_url( $url ) ) {
        return '';
    }

    return $url;
}

/**
 * Get an image URL from an attachment ID, ACF image array or URL.
 *
 * @param mixed  $image Image value.
 * @param string $size  Image size.
 * @return string
 */
function theme_schema_image_url( $image, $size = 'full' ) {
    if ( is_numeric( $image ) ) {
        $image = wp_get_attachment_image_url(
            (int) $image,
            $size
        );
    } elseif ( is_array( $image ) ) {
        if ( ! empty( $image['ID'] ) ) {
            $image = wp_get_attachment_image_url(
                (int) $image['ID'],
                $size
            );
        } elseif ( ! empty( $image['id'] ) ) {
            $image = wp_get_attachment_image_url(
                (int) $image['id'],
                $size
            );
        } else {
            $image = $image['url'] ?? '';
        }
    }

    return theme_schema_url( $image );
}

/**
 * Get the best available image for a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function theme_schema_post_image( $post_id ) {
    $image = get_post_thumbnail_id( $post_id );

    if ( $image ) {
        $url = theme_schema_image_url( $image );

        if ( $url ) {
            return $url;
        }
    }

    $acf_image = theme_schema_get_field(
        'schema_image',
        $post_id
    );

    if ( $acf_image ) {
        return theme_schema_image_url( $acf_image );
    }

    return '';
}

/**
 * Get a post description.
 *
 * Uses an explicit SEO description field if present, then the excerpt.
 * It deliberately does not generate a description from arbitrary HTML.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function theme_schema_post_description( $post_id ) {
    $description = theme_schema_get_field(
        'seo_description',
        $post_id
    );

    if ( ! $description ) {
        $description = theme_schema_get_field(
            'meta_description',
            $post_id
        );
    }

    if ( ! $description ) {
        $description = get_post_field(
            'post_excerpt',
            $post_id
        );
    }

    return theme_schema_text( $description );
}

/**
 * Get the WordPress site logo URL.
 *
 * @return string
 */
function theme_schema_site_logo() {
    $logo_id = get_theme_mod( 'custom_logo' );

    if ( $logo_id ) {
        $logo = theme_schema_image_url( $logo_id );

        if ( $logo ) {
            return $logo;
        }
    }

    return '';
}

/**
 * Get the WordPress site icon URL.
 *
 * @return string
 */
function theme_schema_site_icon() {
    $icon_id = (int) get_option( 'site_icon' );

    if ( ! $icon_id ) {
        return '';
    }

    return theme_schema_image_url( $icon_id );
}

/**
 * Format a valid ISO 8601 date.
 *
 * @param int|false $timestamp Unix timestamp.
 * @return string
 */
function theme_schema_iso_date( $timestamp ) {
    if ( ! $timestamp ) {
        return '';
    }

    return wp_date(
        DATE_W3C,
        (int) $timestamp
    );
}
