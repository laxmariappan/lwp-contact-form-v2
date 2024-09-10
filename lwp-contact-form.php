<?php
/**
 * Plugin Name:       LWP Contact Form
 * Description:       A simple contact form plugin for WordPress.
 * Version:           1.0.0
 * Author:            Lax Mariappan
 * Requires at least: 5.8
 * Requires PHP: 7.4
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
    <div class="lwp-contact-form">
    <form method="post" action="">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" required></textarea>

        <?php // Add a nonce field here for security.
        // @see: https://developer.wordpress.org/reference/functions/wp_nonce_field/
        wp_nonce_field( 'lwp_contact_form_nonce' );
        ?>

        <button type="submit" name="lwp_contact_form_submit">Submit</button>
        <small>* All fields are required.</small>
    </form>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Add styles to the form.
 */
add_action( 'wp_enqueue_scripts', 'wp_learn_enqueue_styles' );
/**
 * Enqueues the styles.
 *
 * @return void
 *
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 */
function wp_learn_enqueue_styles(){
    wp_enqueue_style('lwp-contact-form', plugin_dir_url(__FILE__) . '/assets/css/style.css', array(), '1.0.0', 'all');
}

/**
 * Handles the form submission.
 */
add_action( 'wp', 'wp_learn_handle_submission');
/**
 * Handles the form submission.
 *
 * @return void
 */
function wp_learn_handle_submission() {
    // Check if the form is submitted.
    // We have to verify nonce to secure the form, skipped it for now.
    if ( ! isset( $_POST['lwp_contact_form_submit'] ) ) {
        return;
    }

    // Run a security check.
    // Return if the nonce is invalid.
    // @see: https://developer.wordpress.org/reference/functions/wp_verify_nonce/

    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'lwp_contact_form_nonce' ) ) {
        return;
    }

    /**
     * Add a callback function to print the form data.
     */
    add_action('the_content', 'wp_learn_print_form_data');
}

/**
 * Prints the form data.
 *
 * @param string $content The content.
 *
 * @return string
 */
function wp_learn_print_form_data( $content ) {
    // Sanitize the data.
    $first_name = sanitize_text_field( $_POST['first_name'] );
    $last_name  = sanitize_text_field( $_POST['last_name'] );
    $subject    = sanitize_text_field( $_POST['subject'] );
    $email      = sanitize_email( $_POST['email'] );
    $message    = sanitize_textarea_field( $_POST['message'] );

    //Print the data.

    $data = '<div class="lwp-contact-form success-message">';
    $data .= '<h2>Thank you for your message!</h2>';
    $data .= '<p><strong>First Name:</strong> ' . $first_name . '</p>';
    $data .= '<p><strong>Last Name:</strong> ' . $last_name . '</p>';
    $data .= '<p><strong>Subject:</strong> ' . $subject . '</p>';
    $data .= '<p><strong>Email:</strong> ' . $email . '</p>';
    $data .= '<p><strong>Message:</strong> ' . $message . '</p>';
    $data .= '</div>';

    // Return the content with data.
    return $data . $content;

}