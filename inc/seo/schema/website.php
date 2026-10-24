
<?php
/**
 * WebSite schema.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build WebSite data.
 *
 * @return array
 */
function theme_schema_get_website() {
    $name = theme_schema_text( get_bloginfo( 'name' ) );
    $url  = theme_schema_url( home_url( '/' ) );

    if ( '' === $name || '' === $url ) {
        return array();
    }

    $schema = array(
        '@type' => 'WebSite',
        '@id'   => theme_schema_website_id(),
        'url'   => $url,
        'name'  => $name,
        'publisher' => array(
            '@id' => theme_schema_organization_id(),
        ),
    );

    $language = get_bloginfo( 'language' );

    if ( $language ) {
        $schema['inLanguage'] = sanitize_text_field( $language );
    }

    return $schema;
}
