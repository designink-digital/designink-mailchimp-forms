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

use Designink\WordPress\Framework\v1_0_1\Utility;

if ( ! class_exists( 'Designink_Mailchimp_Shortcode', false ) ) {

	/**
	 * This abstraction holds all of the shared properties between MailChimp forms. (Everything but rendering)
	 */
	abstract class Designink_Mailchimp_Shortcode {

		/**
		 * This function will be called by the primary Designink_Mailchimp_Form::render() function.
		 * 
		 * @param array $attrs The associative array of the shortcode attributes.
		 */
		abstract protected static function render( array $attrs );

		/**
		 * A placeholder function for super classes to modify attribute data with before rendering a form.
		 * 
		 * @param array $attrs The associative array of the shortcode attributes to convert.
		 * 
		 * @return array The modified attributes.
		 */
		abstract public static function set_attributes( array $atts );

		/** @var array Default shortcode attribute values */
		private static $default_attributes = array(
			'button_class'		=> '',
			'button_text'		=> 'Subscribe',
			'form_class'		=> '',
			'groups'			=> '',
			'href'				=> '',
			'input_class'		=> '',
			'labels'			=> '',
			'message_class'		=> '',
			'message_text'		=> 'Thank you for subscribing to our mailing list.',
			'nospam'			=> '',
			'order'				=> '',
			'orientation'		=> 'horizontal',
			'use_placeholders'	=> 'yes',
		);

		/**
		 * The function to be hooked into add_shortcode().
		 * 
		 * @param array $attrs The associative array of the shortcode attributes.
		 */
		final public static function do_shortcode( array $attrs ) {
			$attrs = Utility::guided_array_merge( self::$default_attributes, $attrs );
			$attrs = self::parse_attributes( $attrs );

			if ( $attrs['orientation'] === 'vertical') {
				Designink_Vertical_Mailchimp_Shortcode::render( $attrs );
			} else {
				Designink_Horizontal_Mailchimp_Shortcode::render( $attrs );
			}

		}

		/**
		 * Convert the string stuctured generated for the shortcode attributes and convert them into usable arrays and objects.
		 * 
		 * @param array $attrs The associative array of the shortcode attributes to convert.
		 * 
		 * @return array The modified attributes.
		 */
		final public static function parse_attributes( array $attrs ) {
			$attrs['labels'] = self::parse_labels_attribute( $attrs['labels'] );
			$attrs['order'] = self::parse_order_attribute( $attrs['order'] );
			$attrs['groups'] = self::parse_groups_attribute( $attrs['groups'] );

			// Static class attribute modifications
			return static::set_attributes( $attrs );
		}

		/**
		 * Create a structure for the attribute labels.
		 * 
		 * @param string $labels The string-represented set of labels in the format: "$label_input:$label_text".
		 * 
		 * @return array The array representation of the shortcode labels.
		 */
		public static function parse_labels_attribute( string $labels ) {
			$labels = explode( ',', $labels );
			$structure = array();

			foreach ( $labels as $label ) {
				$pieces = explode( ':', $label );
				$structure[] = array( 'name' => $pieces[0],'label' => $pieces[1] );
			}

			return $structure;
		}

		/**
		 * A quick function to explode the order attributes which should be CSV.
		 * 
		 * @param string $order The CSV string of input names in the order to be displayed.
		 * 
		 * @return array An array which holds the display order of form input names.
		 */
		final public static function parse_order_attribute( string $order ) {
			return explode( ',', $order );
		}

		/**
		 * Create a structure for a string formated group or set of groups.
		 * 
		 * @param string $groups A custom structured string the holds the information for the form groups. String has format of: "$group_id:$group_name{$option_id:$option_name}".
		 * 
		 * @return array The groups structure.
		 */
		final private static function parse_groups_attribute( string $groups ) {
			$group_regex = "/(?<id>\d+):(?<name>[a-zA-Z0-9\s]+){(?<options>(?:\d+:[a-zA-Z0-9\s]+,?)+)}/";
			$structure = array();
			$matches = array();

			preg_match_all( $group_regex, $groups, $matches );

			for ( $i = 0; $i < count( $matches[0] ); $i++ ) {
				$id = $matches['id'][ $i ];
				$name = $matches['name'][ $i ];
				$options = $matches['options'][ $i ];

				$structure[ $i ]['id'] = $id;
				$structure[ $i ]['name'] = $name;
				$structure[ $i ]['options'] = self::parse_group_options( $options );
			}

			return $structure;
		}

		/**
		 * A handy one-liner to take a single set of options for a form group and create an associative array of it.
		 * 
		 * @param string $options The string of options formatted as: "$option_id:$option_value, ... ".
		 * 
		 * @return array The associative array of options contained in the string.
		 */
		final private static function parse_group_options( string $options ) {
			$options = explode( ',', $options );
			$structure = array();

			for ( $i = 0; $i < count( $options ); $i++ ) {
				$pieces = explode( ':', $options[ $i ] );

				$id = $pieces[0];
				$name = $pieces[1];

				$structure[ $i ]['id'] = $id;
				$structure[ $i ]['name'] = $name;
			}

			return $structure;
		}

	}

}