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
 * Contact form class.
 */
class LWP_Contact_Form {

    /**
     * Constructor.
     *
     * Initializes the plugin by setting actions and filters.
     */
    public function __construct() {
        // Enqueue minimal CSS for the form.
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );

        // Handle form submission and display a success message.
        add_action( 'wp', [ $this, 'handle_submission' ] );

        // Register shortcode to render the contact form.
        add_shortcode( 'lwp_contact_form', [ $this, 'render_contact_form' ] );

        //Register custom post type for form entries.
        add_action( 'init', [ $this, 'register_post_type' ] );

        // Add custom columns to the form entry post type.
        add_filter('manage_lwp-form-entry_posts_columns', [ $this, 'add_custom_columns' ] );

        // Display values in custom columns.
        add_action( 'manage_lwp-form-entry_posts_custom_column', [ $this, 'display_custom_columns' ], 10, 2 );
    }

    public function enqueue_styles() {
        // Enqueue minimal CSS for the form.
        // We can move this to a separate CSS file and enqueue it properly.
        echo '<style>
            .lwp-contact-form {
                max-width: 600px;
                margin: 40px auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            .lwp-contact-form input, .lwp-contact-form textarea {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            .lwp-contact-form button {
                padding: 10px 20px;
                background-color: #0073aa;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .lwp-contact-form button:hover {
                background-color: #005177;
            }
            .lwp-contact-form.success-message {
                color: green;
                background: #F2FBF2DB;
                font-weight: bold;
            }
        </style>';
    }

    public function handle_submission() {
        // Check nonce.
        if ( ! isset( $_POST['lwp_contact_form_submit'] ) ) {
            return;
        }

        // Run a security check.
        // Return if the nonce is invalid.
        // @see: https://developer.wordpress.org/reference/functions/wp_verify_nonce/

        if( ! wp_verify_nonce( $_POST['_wpnonce'], 'lwp_contact_form_nonce' ) ) {
            return;
        }

        // Process form data here (sanitization skipped for now).
        $first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
        $last_name = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
        $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
        $email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
        $message = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';
        // Process the data.
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

        // Display success message.
        add_action( 'the_content', function( $content ) use ( $first_name, $last_name, $subject, $email, $message, $post_id ) {
            $success_message = '<div class="lwp-contact-form success-message">';
            $success_message .= '<p>Thank you for your message!</p>';

            if( $post_id ){
                $success_message .= '<p>Form entry saved successfully.</p>';
            }

            $success_message .= '<p>First Name: ' . $first_name . '</p>';
            $success_message .= '<p>Last Name: ' . $last_name . '</p>';
            $success_message .= '<p>Subject: ' . $subject . '</p>';
            $success_message .= '<p>Email: ' . $email . '</p>';
            $success_message .= '<p>Message: ' . $message . '</p>';
            $success_message .= '</div>';

            return $success_message . $content;
        } );


    }

    /**
     * Register custom post type for form entries.
     *
     * @return void
     */
    public function register_post_type(){
        register_post_type( 'lwp-form-entry', [
            'labels' => [
                'name' => 'Form Entries',
                'singular_name' => 'Form Entry',
            ],
            'public' => false,
            'show_ui' => true,
        ] );
    }

    /**
     * Add custom columns to the form entry post type.
     *
     * @param array $columns Existing columns.
     *
     * @return array Modified columns.
     */
    public function add_custom_columns( $columns ) {
        $columns['first_name'] = 'First Name';
        $columns['last_name'] = 'Last Name';
        $columns['email'] = 'Email';
        return $columns;
    }

    /**
     * Display values in custom columns.
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function display_custom_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'first_name':
                echo get_post_meta( $post_id, 'first_name', true );
                break;
            case 'last_name':
                echo get_post_meta( $post_id, 'last_name', true );
                break;
            case 'email':
                echo get_post_meta( $post_id, 'email', true );
                break;
        }
    }

    public function render_contact_form() {
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
}

// Initialize the plugin.
new LWP_Contact_Form();