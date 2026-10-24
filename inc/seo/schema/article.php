
<?php
/**
 * Article schema.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build Article or BlogPosting data.
 *
 * @return array
 */
function theme_schema_get_article() {
    if ( ! is_singular() ) {
        return array();
    }

    $post_id = get_queried_object_id();
    $post    = get_post( $post_id );

    if ( ! $post instanceof WP_Post ) {
        return array();
    }

    $post_type = get_post_type( $post_id );

    $allowed_types = apply_filters(
        'theme_schema_article_post_types',
        array( 'post' )
    );

    if (
        ! is_array( $allowed_types ) ||
        ! in_array( $post_type, $allowed_types, true )
    ) {
        return array();
    }

    if ( 'publish' !== get_post_status( $post_id ) ) {
        return array();
    }

    $title = theme_schema_text(
        get_the_title( $post_id )
    );

    $url = theme_schema_url(
        get_permalink( $post_id )
    );

    if ( '' === $title || '' === $url ) {
        return array();
    }

    $schema = array(
        '@type' => 'BlogPosting',
        '@id'   => $url . '#article',
        'mainEntityOfPage' => array(
            '@id' => theme_schema_webpage_id(),
        ),
        'headline' => $title,
        'publisher' => array(
            '@id' => theme_schema_organization_id(),
        ),
    );

    $description = theme_schema_post_description(
        $post_id
    );

    if ( $description ) {
        $schema['description'] = $description;
    }

    $image = theme_schema_post_image( $post_id );

    if ( $image ) {
        $schema['image'] = array( $image );
    }

    $published = get_post_time(
        DATE_W3C,
        false,
        $post_id
    );

    $modified = get_post_modified_time(
        DATE_W3C,
        false,
        $post_id
    );

    if ( $published ) {
        $schema['datePublished'] = $published;
    }

    if ( $modified ) {
        $schema['dateModified'] = $modified;
    }

    $author_id = (int) $post->post_author;

    if ( $author_id > 0 ) {
        $author_name = theme_schema_text(
            get_the_author_meta( 'display_name', $author_id )
        );

        $author_url = get_author_posts_url( $author_id );

        if ( $author_name ) {
            $author = array(
                '@type' => 'Person',
                'name'  => $author_name,
            );

            if ( $author_url ) {
                $author['url'] = esc_url_raw( $author_url );
            }

            $schema['author'] = $author;
        }
    }

    $language = get_bloginfo( 'language' );

    if ( $language ) {
        $schema['inLanguage'] = sanitize_text_field( $language );
    }

    return $schema;
}
