function DesigninkMailchimpFormData(updateCallback) {
	this.updateCallback = updateCallback;
	this.hrefInput = document.getElementById('mc-form-data-href');
	this.nospamInput = document.getElementById('mc-form-data-nospam');
	this.cssInput = document.getElementById('mc-form-data-classes');

	this.hrefInput.addEventListener('keyup', this.updateCallback);
	this.nospamInput.addEventListener('keyup', this.updateCallback);
	this.cssInput.addEventListener('keyup', this.updateCallback);
}

DesigninkMailchimpFormData.hrefRegex = new RegExp(/<form.+action="([^"]+)".+>/i);
DesigninkMailchimpFormData.nospamRegex = new RegExp(/<input.+name="([^"]+)".+tabindex="-1"[^>]+>/i);

DesigninkMailchimpFormData.prototype.setData = function(html) {
	var href = DesigninkMailchimpFormData.hrefRegex.exec(html)[1],
		nospam = DesigninkMailchimpFormData.nospamRegex.exec(html)[1];

	this.hrefInput.value = href.trim();
	this.nospamInput.value = nospam.trim();
};

DesigninkMailchimpFormData.prototype.populateShortcodeArgs = function(args) {
	if(this.hrefInput.value !== "") {
		args.href = this.hrefInput.value;
	}

	if(this.nospamInput.value !== "") {
		args.nospam = this.nospamInput.value;
	}

	if(this.cssInput.value !== "") {
		args.formClass = this.cssInput.value;
	}

	return args;
};