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

        // Process form data here (sanitization skipped for now).
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $subject = $_POST['subject'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        // Process the data - send an email or save to the database.
        // For now, just display the success message.

        // Display success message.
        add_action( 'the_content', function( $content ) use ( $first_name, $last_name, $subject, $email, $message ) {
            $success_message = '<div class="lwp-contact-form success-message">';
            $success_message .= '<p>Thank you for your message!</p>';
            $success_message .= '<p>First Name: ' . $first_name . '</p>';
            $success_message .= '<p>Last Name: ' . $last_name . '</p>';
            $success_message .= '<p>Subject: ' . $subject . '</p>';
            $success_message .= '<p>Email: ' . $email . '</p>';
            $success_message .= '<p>Message: ' . $message . '</p>';
            $success_message .= '</div>';
            return $success_message . $content;
        } );


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