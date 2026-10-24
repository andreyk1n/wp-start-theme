
<?php
/**
 * BreadcrumbList schema.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add a breadcrumb item.
 *
 * @param array  $items Breadcrumb collection.
 * @param string $name  Label.
 * @param string $url   URL. May be empty for the current page.
 * @return array
 */
function theme_schema_add_breadcrumb(
    $items,
    $name,
    $url = ''
) {
    $name = theme_schema_text( $name );

    if ( '' === $name ) {
        return $items;
    }

    $item = array(
        '@type'    => 'ListItem',
        'position' => count( $items ) + 1,
        'name'     => $name,
    );

    if ( $url ) {
        $url = theme_schema_url( $url );

        if ( $url ) {
            $item['item'] = $url;
        }
    }

    $items[] = $item;

    return $items;
}

/**
 * Build the breadcrumb trail.
 *
 * @return array
 */
function theme_schema_get_breadcrumb() {
    if ( is_front_page() || is_404() || is_search() ) {
        return array();
    }

    $items = array();

    if ( is_singular() ) {
        $post_id = get_queried_object_id();
        $post    = get_post( $post_id );

        if ( ! $post instanceof WP_Post ) {
            return array();
        }

        if ( 'page' === $post->post_type ) {
            $ancestors = array_reverse(
                get_post_ancestors( $post_id )
            );

            foreach ( $ancestors as $ancestor_id ) {
                $items = theme_schema_add_breadcrumb(
                    $items,
                    get_the_title( $ancestor_id ),
                    get_permalink( $ancestor_id )
                );
            }
        } elseif ( 'post' === $post->post_type ) {
            $categories = get_the_category( $post_id );

            if ( ! empty( $categories ) ) {
                $category = $categories[0];

                $category_ancestors = array_reverse(
                    get_ancestors(
                        $category->term_id,
                        'category'
                    )
                );

                foreach ( $category_ancestors as $ancestor_id ) {
                    $ancestor = get_term(
                        $ancestor_id,
                        'category'
                    );

                    if ( $ancestor instanceof WP_Term ) {
                        $items = theme_schema_add_breadcrumb(
                            $items,
                            $ancestor->name,
                            get_term_link( $ancestor )
                        );
                    }
                }

                $items = theme_schema_add_breadcrumb(
                    $items,
                    $category->name,
                    get_category_link( $category->term_id )
                );
            }
        } else {
            /**
             * Custom post type archives can be used as parents.
             */
            $archive_url = get_post_type_archive_link(
                $post->post_type
            );

            if ( $archive_url ) {
                $items = theme_schema_add_breadcrumb(
                    $items,
                    get_post_type_object( $post->post_type )->labels->name
                        ?? $post->post_type,
                    $archive_url
                );
            }
        }

        $items = theme_schema_add_breadcrumb(
            $items,
            get_the_title( $post_id )
        );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();

        if ( ! $term instanceof WP_Term ) {
            return array();
        }

        $taxonomy = $term->taxonomy;

        if ( is_taxonomy_hierarchical( $taxonomy ) ) {
            $ancestors = array_reverse(
                get_ancestors(
                    $term->term_id,
                    $taxonomy
                )
            );

            foreach ( $ancestors as $ancestor_id ) {
                $ancestor = get_term(
                    $ancestor_id,
                    $taxonomy
                );

                if ( $ancestor instanceof WP_Term ) {
                    $items = theme_schema_add_breadcrumb(
                        $items,
                        $ancestor->name,
                        get_term_link( $ancestor )
                    );
                }
            }
        }

        $items = theme_schema_add_breadcrumb(
            $items,
            $term->name
        );
    } elseif ( is_post_type_archive() ) {
        $post_type = get_query_var( 'post_type' );

        if ( is_array( $post_type ) ) {
            $post_type = reset( $post_type );
        }

        $object = $post_type
            ? get_post_type_object( $post_type )
            : null;

        if ( $object ) {
            $items = theme_schema_add_breadcrumb(
                $items,
                $object->labels->name
            );
        }
    } elseif ( is_author() ) {
        $author_id = (int) get_query_var( 'author' );

        $items = theme_schema_add_breadcrumb(
            $items,
            get_the_author_meta( 'display_name', $author_id )
        );
    } elseif ( is_home() ) {
        $posts_page_id = (int) get_option( 'page_for_posts' );

        $items = theme_schema_add_breadcrumb(
            $items,
            $posts_page_id
                ? get_the_title( $posts_page_id )
                : get_bloginfo( 'name' )
        );
    }

    if ( count( $items ) < 2 ) {
        return array();
    }

    return array(
        '@type' => 'BreadcrumbList',
        '@id'   => theme_schema_get_current_url() . '#breadcrumb',
        'itemListElement' => $items,
    );
}
