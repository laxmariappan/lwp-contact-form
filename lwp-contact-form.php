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
        </style>';
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