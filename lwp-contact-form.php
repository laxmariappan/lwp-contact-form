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
        // Register shortcode to render the contact form.
        add_shortcode( 'lwp_contact_form', [ $this, 'render_contact_form' ] );
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