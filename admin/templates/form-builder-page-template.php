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

<div class="mailchimp-form-builder">
	<h1>MailChimp Form Builder by Digital Solutions</h1>

	<div>
		<div class="column half">
			<div class="option-group">
				<h2 class="group-header">Primary Inputs</h2>

				<div id="mc-primary-inputs"></div>

				<table>
					<td><label for="mc-form-inputs-css">Input CSS Classes:</label></td>
					<td><input type="text" id="mc-primary-input-classes" /></td>
				</table>

				<div>
					<input type="button" class="button button-primary" id="mc-primary-input-add" value="Add Option" />
				</div>
			</div>

			<div class="option-group">
				<h2 class="group-header">Orientation</h2>

				<form id="mc-orientation">
					<table>
						<tr>
							<td><label for="mc-form-orientation-horizontal">Horizontal:</label></td>
							<td><input type="radio" name="mc-orientation" value="horizontal" id="mc-form-orientation-horizontal" checked /></td>
						</tr>

						<tr>
							<td><label for="mc-form-orientation-vertical">Vertical:</label></td>
							<td><input type="radio" name="mc-orientation" value="vertical" /></label></td>
						</tr>
					</table>
				</form>
			</div>
		</div>

		<div class="column half">
			<div class="option-group">
				<h2 class="group-header">Form Data</h2>

				<div id="mc-form-data">
					<table>
						<tr>
							<td>Href:</td>
							<td><input type="text" value="" id="mc-form-data-href" /></td>
						</tr>

						<tr>
							<td>No Spam:</td>
							<td><input type="text" value="" id="mc-form-data-nospam" /></td>
						</tr>

						<tr>
							<td>CSS Classes:</td>
							<td><input type="text" value="" id="mc-form-data-classes" /></td>
						</tr>
					</table>
				</div>
			</div>

			<div class="option-group">
				<h2 class="group-header">Submission Options</h2>

				<div id="mc-submission-options">
					<table>
						<tr>
							<td>Submit Button Text:</td>
							<td><input type="text" value="Subscribe" id="mc-submission-button-text" /></td>
						</tr>

						<tr>
							<td>Submit Button CSS Classes:</td>
							<td><input type="text" value="" id="mc-submission-button-css" /></td>
						</tr>

						<tr>
							<td>Submit Message:</td>
							<td><textarea id="mc-submission-message" rows="3">Thank you for subscribing to our mailing list</textarea></td>
						</tr>

						<tr>
							<td>Message CSS Classes:</td>
							<td><input type="text" value="" id="mc-submission-message-css" /></td>
						</tr>
					</table>
				</div>
			</div>
		</div>

	</div>

	<div class="option-group">
		<h2 class="group-header">Interest Groups</h2>

		<div id="mc-group-options"></div>

		<div>
			<input id="mc-add-group-button" type="button" value="Add Group" class="button-primary" />
		</div>
	</div>

	<div class="option-group">
		<h2 class="group-header">Shortcode Generator</h2>

		<textarea id="mc-shortcode-display" rows="5" class="text-center" readonly></textarea>

		<div class="text-center"><input type="button" class="button button-primary button-hero" id="mc-shortcode-copy" value="Copy Shortcode" /></div>
	</div>

	<div class="option-group" id="mc-group-html">
		<h2 class="group-header">Paste HTML</h2>

		<div>
			<textarea id="mc-html-textarea" rows="10"></textarea>
		</div>

		<div>
			<input type="button" id="mc-parse-html-action" class="btn button button-primary button-hero" value="Parse MailChimp Form" />
		</div>
	</div>
</div>