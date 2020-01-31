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

?>

<div class="designink-mailchimp-shortcode <?php echo $attrs['form_class']; ?>">
	<form
		action="<?php echo $attrs['href']; ?>"
		method="POST"
		class="mailchimp <?php echo $attrs['orientation']; ?>"
		target="_blank"
		form-index="<?php echo Designink_Mailchimp_Template_Utils::get_form_count(); ?>"
		message="<?php echo htmlspecialchars( $attrs['message_text'] ); ?>"
	>

		<div class="primary-input-fields">

			<?php foreach ( $attrs['order'] as $input_name ) : ?>

				<div class="primary-input-container">
					<?php echo Designink_Mailchimp_Template_Utils::print_input( $input_name, $attrs ); ?>
				</div>

			<?php endforeach; ?>

		</div>

		<div class="option-inputs">

			<?php echo Designink_Mailchimp_Template_Utils::print_group_options( $attrs ); ?>

		</div>

		<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="<?php echo $attrs['nospam']; ?>" tabindex="-1" value="" /></div>

		<div class="form-submit">
			<?php echo Designink_Mailchimp_Template_Utils::print_submit_button( $attrs, 'vertical' ); ?>
		</div>

	</form>

	<?php echo Designink_Mailchimp_Template_Utils::print_message_container( $attrs ); ?>

	<?php Designink_Mailchimp_Template_Utils::increment_form_count(); ?>

</div>