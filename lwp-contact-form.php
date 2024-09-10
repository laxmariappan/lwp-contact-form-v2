<?php
/**
 * Plugin Name:       LWP Contact Form
 * Description:       A simple contact form plugin for WordPress.
 * Version:           1.0.0
 * Author:            Lax Mariappan
 * Text Domain:       lwp-contact-form
 *
 * @package           lwp-contact-form
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Adds shortcode for the form.
 * Shortcodes are easier to use and can be placed anywhere in the content.
 *
 * @see https://developer.wordpress.org/reference/functions/add_shortcode/
 */
add_shortcode( 'lwp_contact_form', 'wp_learn_render_contact_form' );
/**
 * Renders the contact form.
 *
 * @return string
 */
function wp_learn_render_contact_form() {
    ob_start();
    ?>
    <p>Placeholder for the contact form.</p>
    <?php
    return ob_get_clean();
}