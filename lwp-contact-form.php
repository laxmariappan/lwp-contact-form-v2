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
    $first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
    $last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
    $subject    = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
    $email      = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $message    = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    // Save data to the database as lwp-form-entry post type.
    $post_id = wp_insert_post( [
        'post_title' => 'Form submission - ' . $subject,
        'post_content' => $message,
        'post_status' => 'publish',
        'post_type' => 'lwp-form-entry',
        'meta_input' => [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
        ],
    ] );

    //Print the data.

    $data = '<div class="lwp-contact-form success-message">';
    $data .= '<h2>Thank you for your message!</h2>';


    // Let the user know if the form entry is saved.
    if( $post_id ){
        $data .= '<p>Form entry saved successfully.</p>';
    }

    $data .= '<p><strong>First Name:</strong> ' . $first_name . '</p>';
    $data .= '<p><strong>Last Name:</strong> ' . $last_name . '</p>';
    $data .= '<p><strong>Subject:</strong> ' . $subject . '</p>';
    $data .= '<p><strong>Email:</strong> ' . $email . '</p>';
    $data .= '<p><strong>Message:</strong> ' . $message . '</p>';
    $data .= '</div>';

    // Return the content with data.
    return $data . $content;

}

/**
 * Register the custom post type for form entries.
 */
add_action( 'init', 'wp_learn_register_post_type' );
/**
 * Registers the custom post type for form entries.
 *
 * @return void
 */
function wp_learn_register_post_type() {

    // Register the custom post type.
    // @see https://developer.wordpress.org/reference/functions/register_post_type/
    register_post_type( 'lwp-form-entry', [
        'labels' => [
            'name' => 'Form Entries',
            'singular_name' => 'Form Entry',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => [ 'title', 'editor', 'custom-fields' ],
    ] );
}

/**
 * Show the first name, last name and email in the admin.
 */
add_filter( 'manage_lwp-form-entry_posts_columns', 'wp_learn_add_columns' );
/**
 * Adds custom columns to the form entry post type.
 *
 * @param array $columns Existing columns.
 *
 * @return array Modified columns.
 */
function wp_learn_add_columns( $columns ) {
    $columns['first_name'] = 'First Name';
    $columns['last_name'] = 'Last Name';
    $columns['email'] = 'Email';
    return $columns;
}

/**
 * Display values in custom columns.
 */
add_action( 'manage_lwp-form-entry_posts_custom_column', 'wp_learn_display_columns', 10, 2 );
/**
 * Displays the values in custom columns.
 *
 * @param string $column  The column name.
 * @param int    $post_id The post ID.
 *
 * @return void
 */
function wp_learn_display_columns( $column, $post_id )
{
    switch ($column) {
        case 'first_name':
            echo get_post_meta($post_id, 'first_name', true);
            break;
        case 'last_name':
            echo get_post_meta($post_id, 'last_name', true);
            break;
        case 'email':
            echo get_post_meta($post_id, 'email', true);
            break;
    }
}