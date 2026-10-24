
<?php
/**
 * WebPage and CollectionPage schema.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build WebPage data.
 *
 * @return array
 */
function theme_schema_get_webpage() {
    $url = theme_schema_get_current_url();

    if ( '' === $url ) {
        return array();
    }

    $title       = '';
    $description = '';
    $image       = '';
    $type        = 'WebPage';

    if ( is_front_page() ) {
        $title = get_bloginfo( 'name' );

        $description = get_bloginfo( 'description' );
    } elseif ( is_singular() ) {
        $post_id = get_queried_object_id();

        $title = get_the_title( $post_id );

        $description = theme_schema_post_description(
            $post_id
        );

        $image = theme_schema_post_image( $post_id );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();

        if ( $term instanceof WP_Term ) {
            $title = single_term_title( '', false );

            $description = term_description(
                $term->term_id,
                $term->taxonomy
            );

            $description = wp_strip_all_tags(
                $description,
                true
            );

            $type = 'CollectionPage';
        }
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
        $type  = 'CollectionPage';
    } elseif ( is_home() ) {
        $title = get_bloginfo( 'name' );
        $type  = 'CollectionPage';
    } elseif ( is_author() ) {
        $title = get_the_author_meta(
            'display_name',
            (int) get_query_var( 'author' )
        );

        $type = 'CollectionPage';
    }

    $title = theme_schema_text( $title );

    if ( '' === $title ) {
        $title = theme_schema_text( wp_get_document_title() );
    }

    $schema = array(
        '@type' => $type,
        '@id'   => theme_schema_webpage_id(),
        'url'   => $url,
        'name'  => $title,
        'isPartOf' => array(
            '@id' => theme_schema_website_id(),
        ),
        'publisher' => array(
            '@id' => theme_schema_organization_id(),
        ),
    );

    if ( $description ) {
        $schema['description'] = theme_schema_text(
            $description
        );
    }

    if ( $image ) {
        $schema['primaryImageOfPage'] = array(
            '@type' => 'ImageObject',
            'url'   => $image,
        );
    }

    $language = get_bloginfo( 'language' );

    if ( $language ) {
        $schema['inLanguage'] = sanitize_text_field( $language );
    }

    if ( is_singular() ) {
        $post_id = get_queried_object_id();

        $published = get_post_time( 'c', false, $post_id );
        $modified  = get_post_modified_time( 'c', false, $post_id );

        if ( $published ) {
            $schema['datePublished'] = $published;
        }

        if ( $modified ) {
            $schema['dateModified'] = $modified;
        }
    }

    return $schema;
}
