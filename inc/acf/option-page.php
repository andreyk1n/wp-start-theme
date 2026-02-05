<?php
/**
 * ACF Option Page – Contacts
 */

if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Contacts',
        'menu_title' => 'Contacts',
        'menu_slug' => 'contacts',
        'redirect' => false,
    ]);
}

/**
 * Output helpers
 */

function the_option_phone()
{
    $phone = get_field('phone', 'option');
    if (!$phone)
        return;

    $tel = preg_replace('/[^0-9+]/', '', $phone);

    echo '<a href="tel:' . esc_attr($tel) . '">' . esc_html($phone) . '</a>';
}

function the_option_email()
{
    $email = get_field('email', 'option');
    if (!$email)
        return;

    echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
}

function the_option_location()
{
    $location = get_field('location', 'option');
    if (!$location)
        return;

    echo esc_html($location);
}
