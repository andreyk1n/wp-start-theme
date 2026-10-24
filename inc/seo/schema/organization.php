
<?php
/**
 * Organization schema.
 *
 * @package YourTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build Organization data.
 *
 * Configure via the theme_schema_organization filter.
 *
 * Supported keys:
 * name, url, logo, description, email, telephone,
 * sameAs, type.
 *
 * @return array
 */
function theme_schema_get_organization() {
    $config = array(
        'name'        => get_bloginfo( 'name' ),
        'url'         => home_url( '/' ),
        'logo'        => theme_schema_site_logo(),
        'description' => get_bloginfo( 'description' ),
        'email'       => '',
        'telephone'   => '',
        'sameAs'      => array(),
        'type'        => 'Organization',
    );

    $config = apply_filters(
        'theme_schema_organization',
        $config
    );

    if ( ! is_array( $config ) ) {
        return array();
    }

    $name = theme_schema_text(
        $config['name'] ?? ''
    );

    $url = theme_schema_url(
        $config['url'] ?? home_url( '/' )
    );

    if ( '' === $name || '' === $url ) {
        return array();
    }

    $schema = array(
        '@type' => 'Organization',
        '@id'   => theme_schema_organization_id(),
        'name'  => $name,
        'url'   => $url,
    );

    $logo = theme_schema_url(
        $config['logo'] ?? ''
    );

    if ( $logo ) {
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url'   => $logo,
        );
    }

    $description = theme_schema_text(
        $config['description'] ?? ''
    );

    if ( $description ) {
        $schema['description'] = $description;
    }

    $email = sanitize_email(
        $config['email'] ?? ''
    );

    if ( $email && is_email( $email ) ) {
        $schema['email'] = $email;
    }

    $telephone = theme_schema_text(
        $config['telephone'] ?? ''
    );

    if ( $telephone ) {
        $schema['telephone'] = $telephone;
    }

    $same_as = array();

    foreach ( (array) ( $config['sameAs'] ?? array() ) as $social_url ) {
        $social_url = theme_schema_url( $social_url );

        if ( $social_url ) {
            $same_as[] = $social_url;
        }
    }

    if ( $same_as ) {
        $schema['sameAs'] = array_values(
            array_unique( $same_as )
        );
    }

    /**
     * Use a specific Organization subtype only when appropriate.
     * Example: OnlineStore or a specific LocalBusiness subtype.
     */
    $allowed_types = array(
        'Organization',
        'Corporation',
        'OnlineStore',
        'LocalBusiness',
        'ProfessionalService',
        'Store',
        'MedicalBusiness',
        'Restaurant',
    );

    $type = theme_schema_text(
        $config['type'] ?? 'Organization'
    );

    if ( in_array( $type, $allowed_types, true ) ) {
        $schema['@type'] = $type;
    }

    return $schema;
}
