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

if ( ! class_exists( 'Designink_Horizontal_Mailchimp_Shortcode', false ) ) {

	/**
	 * A static class for dealing with the 'horizontal' MailChimp form functionality.
	 */
	class Designink_Horizontal_Mailchimp_Shortcode extends Designink_Mailchimp_Shortcode {

		/**
		 * An inherited abstract function for setting static attribute properties.
		 * 
		 * @param array $attrs The associative array of the shortcode attributes.
		 * 
		 * @return array The modified attributes.
		 */
		final public static function set_attributes( array $attrs ) {
			$attrs['orientation'] = 'horizontal';
			return $attrs;
		}

		/**
		 * The inherited abstract for printing the form.
		 * 
		 * @param array $attrs The form attributes.
		 */
		final public static function render( array $attrs ) {
			Designink_Mailchimp_Forms_Plugin::instance()->get_template( 'horizontal-mailchimp-form', array( 'attrs' => $attrs ) );
		}

	}

}