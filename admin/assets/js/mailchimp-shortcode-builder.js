document.addEventListener('DOMContentLoaded', function() {
	var builder = new DesigninkMailchimpFormBuilder();
	builder.init();
	builder.updateShortcode();
});

function DesigninkMailchimpFormBuilder() {
	var updateCallback = this.updateShortcode.bind(this);

	this.primaryInputs = new DesigninkMailchimpPrimaryInputList(updateCallback);
	this.groupOptions = new DesigninkMailchimpGroupList(updateCallback);
	this.formData = new DesigninkMailchimpFormData(updateCallback);
	this.submissionOptions = new DesigninkMailchimpSubmissionOptions(updateCallback);

	this.orientationOption = document.getElementById('mc-orientation');

	var that = this;

	this.orientationOption.addEventListener('change', function() {
		that.updateShortcode();
	});
	
	this.htmlParseTextarea = document.getElementById('mc-html-textarea');
	this.htmlParseButton = document.getElementById('mc-parse-html-action');

	this.shortcodeContainer = document.getElementById('mc-shortcode-display');
	this.shortcodeCopy = document.getElementById('mc-shortcode-copy');

	this.shortcodeCopy.addEventListener('click', function() {
		that.copyShortcode();
	});
}

DesigninkMailchimpFormBuilder.prototype.init = function() {
	var that = this;

	this.htmlParseButton.addEventListener('click', function() {
		that.parseHtml();
	});
};

DesigninkMailchimpFormBuilder.prototype.parseHtml = function() {
	var html = this.htmlParseTextarea.value;

	this.primaryInputs.setInputList(this.primaryInputs.parseHtml(html));
	this.primaryInputs.render();

	this.groupOptions.setGroups(this.groupOptions.parseHtml(html));
	this.groupOptions.render();

	this.formData.setData(html);

	this.updateShortcode();
};

DesigninkMailchimpFormBuilder.prototype.updateShortcode = function() {
	var args = {};

	args = this.primaryInputs.populateShortcodeArgs(args);
	args = this.groupOptions.populateShortcodeArgs(args);
	args = this.formData.populateShortcodeArgs(args);
	args = this.submissionOptions.populateShortcodeArgs(args);
	args.orientation = this.orientationOption['mc-orientation'].value;

	this.createShortcode(args);
};

DesigninkMailchimpFormBuilder.prototype.copyShortcode = function() {
	var that = this;

	this.shortcodeContainer.select();
	document.execCommand("copy");
	window.getSelection().removeAllRanges();
	this.shortcodeCopy.value = "Shortcode Copied!";
	this.shortcodeCopy.className = "button button-secondary button-hero";

	setTimeout(function() {
		that.shortcodeCopy.value = "Copy Shortcode";
		that.shortcodeCopy.className = "button button-primary button-hero";
	}, 2000);
}

DesigninkMailchimpFormBuilder.prototype.createShortcode = function(args) {
	argStrings = [];

	args['order'] ? argStrings.push('order="'+args['order']+'"') : null;
	args['labels'] ? argStrings.push('labels="'+args['labels']+'"') : null;
	args['inputClass'] ? argStrings.push('input_class="'+args['inputClass']+'"') : null;

	args['groups'] ? argStrings.push('groups="'+args['groups']+'"') : null;

	args['href'] ? argStrings.push('href="'+args['href']+'"') : null;
	args['nospam'] ? argStrings.push('nospam="'+args['nospam']+'"') : null;
	args['formClass'] ? argStrings.push('form_class="'+args['formClass']+'"') : null;

	args['buttonText'] ? argStrings.push('button_text="'+args['buttonText']+'"') : null;
	args['buttonClass'] ? argStrings.push('button_class="'+args['buttonClass']+'"') : null;
	args['messageText'] ? argStrings.push('message_text="'+args['messageText']+'"') : null;
	args['messageClass'] ? argStrings.push('message_class="'+args['messageClass']+'"') : null;

	var shortcode = "[mailchimp-" + args['orientation'] + " " + argStrings.join(' ') + "]";
	this.shortcodeContainer.innerHTML = shortcode;
};