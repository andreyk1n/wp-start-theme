
<?php
/**
 * Schema.org JSON-LD integration.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/schema/helpers.php';
require_once __DIR__ . '/schema/organization.php';
require_once __DIR__ . '/schema/website.php';
require_once __DIR__ . '/schema/webpage.php';
require_once __DIR__ . '/schema/article.php';
require_once __DIR__ . '/schema/breadcrumb.php';

/**
 * Check whether a third-party SEO plugin owns structured data.
 *
 * @return bool
 */
function theme_schema_has_seo_plugin() {
    $detected = (
        defined( 'WPSEO_VERSION' ) ||
        defined( 'RANK_MATH_VERSION' ) ||
        defined( 'AIOSEO_VERSION' ) ||
        defined( 'SEOPRESS_VERSION' ) ||
        class_exists( '\WPSEO_Options' ) ||
        class_exists( '\RankMath\Helper' ) ||
        function_exists( 'aioseo' ) ||
        function_exists( 'seopress_init' )
    );

    return (bool) apply_filters(
        'theme_schema_seo_plugin_detected',
        $detected
    );
}

/**
 * Determine whether the current request should output schema.
 *
 * @return bool
 */
function theme_schema_is_enabled() {
    if (
        is_admin() ||
        is_feed() ||
        is_robots() ||
        is_trackback() ||
        is_preview() ||
        is_404() ||
        is_search()
    ) {
        return false;
    }

    if ( theme_schema_has_seo_plugin() ) {
        return false;
    }

    return (bool) apply_filters(
        'theme_schema_enabled',
        true
    );
}

/**
 * Build the complete JSON-LD graph.
 *
 * @return array
 */
function theme_schema_build_graph() {
    $graph = array();

    $organization = theme_schema_get_organization();

    if ( ! empty( $organization ) ) {
        $graph[] = $organization;
    }

    $website = theme_schema_get_website();

    if ( ! empty( $website ) ) {
        $graph[] = $website;
    }

    $webpage = theme_schema_get_webpage();

    if ( ! empty( $webpage ) ) {
        $graph[] = $webpage;
    }

    $article = theme_schema_get_article();

    if ( ! empty( $article ) ) {
        $graph[] = $article;
    }

    $breadcrumb = theme_schema_get_breadcrumb();

    if ( ! empty( $breadcrumb ) ) {
        $graph[] = $breadcrumb;
    }

    /**
     * Allow adding or modifying graph entities.
     */
    $graph = apply_filters( 'theme_schema_graph', $graph );

    if ( ! is_array( $graph ) ) {
        return array();
    }

    $graph = array_values(
        array_filter(
            $graph,
            'is_array'
        )
    );

    return $graph;
}

/**
 * Output JSON-LD in the document head.
 *
 * @return void
 */
function theme_schema_output() {
    if ( ! theme_schema_is_enabled() ) {
        return;
    }

    $graph = theme_schema_build_graph();

    if ( empty( $graph ) ) {
        return;
    }

    $data = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    );

    $json = wp_json_encode(
        $data,
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    );

    if ( ! is_string( $json ) || '' === $json ) {
        return;
    }

    echo "\n" . '<script type="application/ld+json">' .
        $json .
        '</script>' . "\n";
}
add_action( 'wp_head', 'theme_schema_output', 30 );
