<?php
/**
 * Plugin Name: DesignInk MailChimp Forms
 * Plugin ID: di-update/plugin/designink-mailchimp-forms
 * Plugin URI: https://designinkdigital.com/
 * Description: A plugin for creating beautiful, easy-to-use, embedded MailChimp sign-up forms for your WordPress site.
 * Version: 2.2.0
 * Author: DesignInk Digital
 * Author URI: https://designinkdigital.com/
 * Text Domain: wporg
 * Domain Path: /languages
 * 
 * Copyright: (c) 2008-2020, DesignInk, LLC (answers@designinkdigital.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2020, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * 
 */

defined( 'ABSPATH' ) or exit;

use Designink\WordPress\Framework\v1_0_1\Plugin;
use Designink\WordPress\Plugin_Update_Helper\v1_0_0\Plugin_Helper_Update_List;

// Include DesignInk's framework
require_once __DIR__ . '/vendor/designink/designink-wp-framework/index.php';

// Include the plugin update helper
require_once __DIR__ . '/vendor/designink/plugin-update-helper/index.php';

if ( ! class_exists( 'Designink_Mailchimp_Forms_Plugin', false ) ) {

	/**
	 * The plugin wrapper class.
	 */
	final class Designink_Mailchimp_Forms_Plugin extends Plugin {

		/**
		 * Plugin entry point
		 */
		final public static function construct() {
			Plugin_Helper_Update_List::add_plugin( 'designink-mailchimp-forms', 'https://designinkdigital.com/' );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, '_wp_enqueue_scripts' ) );
		}

		/**
		 * The WordPress 'wp_enqueue_scripts' hook.
		 */
		final public static function _wp_enqueue_scripts() {
			$Plugin = self::instance();
			$Plugin->enqueue_css( 'designink-mailchimp-shortcode' );
			$Plugin->enqueue_js( 'designink-mailchimp-shortcode' );
		}

	}

	// Start it up.
	Designink_Mailchimp_Forms_Plugin::instance();
}
