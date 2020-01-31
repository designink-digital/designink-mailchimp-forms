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

if ( ! class_exists( 'Designink_Mailchimp_Template_Utils', false ) ) {

	/**
	 * A set of static functions to help print inputs and information for MailChimp form templates.
	 */
	final class Designink_Mailchimp_Template_Utils {

		/** @var int A counter for how many forms have been printed in a single document. */
		protected static $form_count_index = 0;

		/**
		 * Return the form count index.
		 * 
		 * @return int The current form count index.
		 */
		final public static function get_form_count() { return self::$form_count_index; }

		/**
		 * Increment the form count index.
		 */
		final public static function increment_form_count() { self::$form_count_index++; }

		/**
		 * A wrapper function for printing a form input. Decides whether or not to print the input label.
		 * 
		 * @param string $input_name The name of the input being printed.
		 * @param array $attrs The form attributes.
		 */
		final public static function print_input( string $input_name, array $attrs ) {

			if ( $attrs['use_placeholders'] === 'no') {
				self::print_input_with_label( $input_name, $attrs );
			} else {
				self::print_input_field( $input_name, $attrs );
			}

		}

		/**
		 * Print an input field surrounded by a label.
		 * 
		 * @param string $input_name The name of the input being printed.
		 * @param array $attrs The form attributes.
		 */
		final public static function print_input_with_label( string $input_name, array $attrs ) {
			$input_label = 'Default';

			foreach ( $attrs['labels'] as $label ) {
				if ( $label['name'] === $input_name ) {
					$input_label = $label['label'];
				}
			}

			?>

				<label for="mailchimp-form-<?php echo self::$form_count_index; ?>-<?php echo $input_name; ?>">
					<?php echo $input_label; ?>
					<?php self::print_input_field( $input_name, $attrs ) ?>
				</label>

			<?php
		}

		/**
		 * Print a simple input field.
		 * 
		 * @param string $input_name The name of the input being printed.
		 * @param array $attrs The form attributes.
		 */
		final public static function print_input_field( string $input_name, array $attrs ) {
			$input_label = 'Default';

			foreach($attrs['labels'] as $label) {
				if($label['name'] == $input_name) {
					$input_label = $label['label'];
				}
			}

			?>

				<input
					id="mailchimp-form-<?php echo self::$form_count_index; ?>-<?php echo $input_name; ?>"
					class="<?php echo $attrs['input_class']; ?>"
					name="<?php echo $input_name; ?>"
					type="text"
					<?php echo $attrs['use_placeholders'] !== 'no' ? sprintf( 'placeholder="%s"', $input_label ) : ''; ?>
				/>

			<?php
		}

		/**
		 * Print a checkbox and label for a single option of a form group.
		 * 
		 * @param array $group The group configuration options (ID).
		 * @param array $group The option configuration (name and ID).
		 */
		final public static function print_group_option( array $group, array $option ) {
			?>

				<label for="mailchimp-form-<?php echo self::$form_count_index; ?>-<?php echo $group['id']; ?>-<?php echo $option['id']; ?>">
					<input
						type="checkbox"
						value="<?php echo $option['id']; ?>"
						name="group[<?php echo $group['id']; ?>][<?php echo $option['id']; ?>]"
						id="mailchimp-form-<?php echo self::$form_count_index; ?>-<?php echo $group['id']; ?>-<?php echo $option['id']; ?>"
					/>
					<?php echo $option['name']; ?>
				</label>

			<?php
		}

		/**
		 * Print a submit button for a form.
		 * 
		 * @param array $attrs The form attributes.
		 * @param string $orientation An override for the button orientation.
		 */
		final public static function print_submit_button( array $attrs, string $orientation ) {
			?>

				<input type="submit" class="btn <?php echo $attrs['button_class']; ?> <?php echo $orientation; ?>" value="<?php echo $attrs['button_text']; ?>" />

			<?php
		}

		/**
		 * 
		 */
		final public static function print_group_options( array $attrs ) {
			?>

				<?php foreach ( $attrs['groups'] as $group ) : ?>

					<div class="group-options">
						<div class="group-title">
							<strong><?= $group['name'] ?></strong>
						</div>

						<ul class="group-inputs">

							<?php foreach($group['options'] as $option) : ?>

								<li class="group-input-container">
									<?php echo self::print_group_option( $group, $option ); ?>
								</li>

							<?php endforeach; ?>

						</ul>
					</div>

				<?php endforeach; ?>

			<?php
		}

		/**
		 * 
		 */
		final public static function print_message_container( array $attrs ) {
			?>

				<div class="submit-message">
					<?php echo $attrs['message_text']; ?>
				</div>

			<?php
		}

	}

}
