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
    }
}

// Initialize the plugin.
new LWP_Contact_Form();