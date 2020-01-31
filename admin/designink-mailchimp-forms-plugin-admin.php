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

use Designink\WordPress\Framework\v1_0_1\Plugin\Admin_Module;

if ( ! class_exists( 'Designink_Mailchimp_Forms_Admin', false ) ) {

	/**
	 * The admin functionality for the Designink Mailchimp Forms Plugin.
	 * 
	 * @since 3.0.0
	 */
	final class Designink_Mailchimp_Forms_Plugin_Admin extends Admin_Module {

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			add_action( 'admin_menu', array( __CLASS__, '_admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, '_admin_enqueue_scripts' ) );
		}

		/**
		 * WordPress 'admin_enqueue_scripts' hook.
		 */
		final public static function _admin_enqueue_scripts() {
			if ( get_current_screen()->id === 'tools_page_designink-mailchimp-form-builder' ) {
				$Plugin_Admin = self::instance();
				$Plugin_Admin->enqueue_css( 'mailchimp-shortcode-builder' );
				$Plugin_Admin->enqueue_js( 'mailchimp-shortcode-builder' );
				$Plugin_Admin->enqueue_js( 'mailchimp-primary-inputs-controller' );
				$Plugin_Admin->enqueue_js( 'mailchimp-group-options-controller' );
				$Plugin_Admin->enqueue_js( 'mailchimp-form-data-controller' );
				$Plugin_Admin->enqueue_js( 'mailchimp-submission-options-controller' );
			}
		}

		/**
		 * WordPress 'admin_menu' hook.
		 */
		final public static function _admin_menu() {
			add_management_page(
				'Designink MailChimp Form Builder',
				'MailChimp Form Builder',
				'read',
				'designink-mailchimp-form-builder',
				array( __CLASS__, 'render_form_builder_page' )
			);
		}

		/**
		 * A one-liner for calling the form builder page template.
		 */
		final public static function render_form_builder_page() {
			$Plugin_Admin = self::instance();
			$Plugin_Admin->get_template( 'form-builder-page' );
		}

	}

}
