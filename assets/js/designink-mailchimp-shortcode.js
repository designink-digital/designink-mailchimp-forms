document.addEventListener('DOMContentLoaded', function() {
	var mailchimpForms = document.querySelectorAll('form.mailchimp');

	for(var i = 0; i < mailchimpForms.length; i ++) {
		mailchimpForms[i].addEventListener('submit', function() {
			var message = this.getAttribute('message');

			for(var j = 0; j < mailchimpForms.length; j++) {
				mailchimpForms[j].parentElement.setAttribute('submitted', '');
			}
		});
	}
});