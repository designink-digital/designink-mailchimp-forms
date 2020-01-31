function DesigninkMailchimpSubmissionOptions(updateCallback) {
	this.updateCallback = updateCallback;

	this.buttonTextInput = document.getElementById('mc-submission-button-text');
	this.buttonCssInput = document.getElementById('mc-submission-button-css');
	this.messageTextInput = document.getElementById('mc-submission-message');
	this.messageCssInput = document.getElementById('mc-submission-message-css');

	this.buttonTextInput.addEventListener('keyup', this.updateCallback);
	this.buttonCssInput.addEventListener('keyup', this.updateCallback);
	this.messageTextInput.addEventListener('keyup', this.updateCallback);
	this.messageCssInput.addEventListener('keyup', this.updateCallback);
}

DesigninkMailchimpSubmissionOptions.prototype.populateShortcodeArgs = function(args) {
	if(this.buttonTextInput.value !== "") {
		args.buttonText = this.buttonTextInput.value;
	}

	if(this.buttonCssInput.value !== "") {
		args.buttonClass = this.buttonCssInput.value;
	}

	if(this.messageTextInput.value !== "") {
		args.messageText = this.messageTextInput.value;
	}

	if(this.messageCssInput.value !== "") {
		args.messageClass = this.messageCssInput.value;
	}

	return args;
};