<?php
/**
 * DesignInk MailChimp Forms Plugin
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to answers@designdigitalsolutions.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the plugin to newer
 * versions in the future. If you wish to customize the plugin for your
 * needs please refer to https://designinkdigital.com
 *
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2020, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use Designink\WordPress\Framework\v1_0_1\Module;

if ( ! class_exists( 'Designink_Mailchimp_Shortcode_Module', false ) ) {

	/**
	 * This module deals with registering the form shortcodes.
	 */
	final class Designink_Mailchimp_Shortcode_Module extends Module {

		/**
		 * Module entry
		 */
		final public static function construct() {
			add_action( 'init', array( __CLASS__, '_init' ) );
		}

		/**
		 * WordPress 'init' hook
		 */
		final public static function _init() {
			self::add_shortcodes();
		}

		/**
		 * Register the shortcodes to the render callbacks.
		 */
		final public static function add_shortcodes() {
			add_shortcode( 'mailchimp', array( Designink_Mailchimp_Shortcode::class, 'do_shortcode' ) );
			add_shortcode( 'mailchimp-horizontal', array( Designink_Horizontal_Mailchimp_Shortcode::class, 'do_shortcode' ) );
			add_shortcode( 'mailchimp-vertical', array( Designink_Vertical_Mailchimp_Shortcode::class, 'do_shortcode' ) );
		}

	}

}