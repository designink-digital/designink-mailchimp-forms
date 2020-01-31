/**
 * Input List
 */

function DesigninkMailchimpPrimaryInputList(updateCallback) {
	this.updateCallback = updateCallback;
	this.primaryInputs = [];
	this.container = document.getElementById('mc-primary-inputs');
	this.cssInput = document.getElementById('mc-primary-input-classes');
	this.addInput = document.getElementById('mc-primary-input-add');

	this.cssInput.addEventListener('keyup', this.updateCallback);
	this.addInput.addEventListener('click', this.addOption.bind(this));
}

DesigninkMailchimpPrimaryInputList.primaryInputRegex = new RegExp(/<label[^>]+>([a-zA-Z0-9 _-]+)(?:[a-zA-Z0-9\s<>"=*\/]+)<\/label>\s+<input[^>]+type="([a-zA-Z0-9_-]+)"[^>]+name="([a-zA-Z0-9_-]+)"[^>]+>/gi);

DesigninkMailchimpPrimaryInputList.prototype.populateShortcodeArgs = function(args) {
	if(Array.isArray(this.primaryInputs)) {
		var orderArgs = [],
			labelArgs = [];

		for(var i = 0; i < this.primaryInputs.length; i++) {
			if(this.primaryInputs[i].active) {
				var name = this.primaryInputs[i].name;
				var labels = this.primaryInputs[i].name + ":" + this.primaryInputs[i].label;

				orderArgs.push(name);
				labelArgs.push(labels);
			}
		}

		args.order = orderArgs.join(',');
		args.labels = labelArgs.join(',');
		
		if(this.cssInput.value !== "") {
			args.inputClass = this.cssInput.value;
		}
	}

	return args;
};

DesigninkMailchimpPrimaryInputList.prototype.parseHtml = function(html) {
	var match = DesigninkMailchimpPrimaryInputList.primaryInputRegex.exec(html);
	var matches = [];

	if(Array.isArray(match)) {
		while(match != null) {
			var label = match[1].trim();
			var type = match[2];
			var name = match[3];

			matches.push(new DesigninkMailchimpPrimaryInput(label, type, name, this));
			match = DesigninkMailchimpPrimaryInputList.primaryInputRegex.exec(html);
		}
	}

	DesigninkMailchimpPrimaryInputList.primaryInputRegex.lastIndex = 0;

	return matches;
};

DesigninkMailchimpPrimaryInputList.prototype.addOption = function() {
	this.primaryInputs.push(new DesigninkMailchimpPrimaryInput("New Option", "text", "MMERGE#", this));
	this.render();
};

DesigninkMailchimpPrimaryInputList.prototype.setInputList = function(inputList = []) {

	if(Array.isArray(inputList)) {
		for(var i = 0; i < inputList.length; i++) {
			if(!(inputList[i] instanceof DesigninkMailchimpPrimaryInput)) {
				console.error("Non-instance of DesigninkMailchimpPrimaryInput passed to DesigninkMailchimpPrimaryInputList::setInputList()");
				return;
			}
		}

		this.primaryInputs = inputList;
		this.setInputIndices();
	}
};

DesigninkMailchimpPrimaryInputList.prototype.setInputIndices = function() {
	for(var i = 0; i < this.primaryInputs.length; i++) {
		this.primaryInputs[i].setIndex(i);
	}
}

DesigninkMailchimpPrimaryInputList.prototype.render = function() {
	this.container.innerHTML = "";

	for(var i = 0; i < this.primaryInputs.length; i++) {
		this.container.appendChild(this.primaryInputs[i].element);
	}

	this.setInputIndices();
	this.updateCallback();
};

DesigninkMailchimpPrimaryInputList.prototype.removeInput = function(index) {
	if(typeof index !== 'number') return;

	if(index < this.primaryInputs.length && index >= 0) {
		this.primaryInputs.splice(index, 1);
		this.render();
	}
};

DesigninkMailchimpPrimaryInputList.prototype.moveInputUp = function(index) {
	if(typeof index !== 'number') return;

	if(index > 0) {
		this.primaryInputs.splice((index-1), 0, this.primaryInputs.splice(index, 1)[0]);
		this.render();
	}
};

DesigninkMailchimpPrimaryInputList.prototype.moveInputDown = function(index) {
	if(typeof index !== 'number') return;

	if(index < this.primaryInputs.length) {
		this.primaryInputs.splice((index+1), 0, this.primaryInputs.splice(index, 1)[0]);
		this.render();
	}
};


/**
 * Single Input
 */

function DesigninkMailchimpPrimaryInput(label, type, name, parentList) {
	if(!(parentList instanceof DesigninkMailchimpPrimaryInputList)) {
		console.error("Non-instance of DesigninkMailchimpPrimaryInputList passed to DesigninkMailchimpPrimaryInput()");
		return;
	}

	this.parentList = parentList;
	this.active = true;
	this.index = 0;
	this.label = label;
	this.name = name;
	this.type = type;
	this.element = this.generateElement();
}

DesigninkMailchimpPrimaryInput.prototype.setIndex = function(index) {
	this.index = index;
}

DesigninkMailchimpPrimaryInput.prototype.generateElement = function() {
	var container = document.createElement('div');

	container.appendChild(this.generateOrderControls());
	container.appendChild(this.generateActiveControl());
	container.appendChild(this.generateInputsControl());
	container.appendChild(this.generateRemoveControl());

	container.className = "primary-input-container";

	return container;
};

DesigninkMailchimpPrimaryInput.prototype.generateRemoveControl = function() {
	var container = document.createElement('div');
	var removeControl = document.createElement('i');
	var that = this;

	removeControl.className = "dashicons-before dashicons-no remove";
	container.className = "primary-input-remove-control-container inline-block";

	container.appendChild(removeControl);

	removeControl.addEventListener('click', function() {
		that.parentList.removeInput(that.index);
	});

	return container;
};

DesigninkMailchimpPrimaryInput.prototype.generateOrderControls = function() {
	var container = document.createElement('div');
	var upControl = document.createElement('i');
	var downControl = document.createElement('i');
	var that = this;

	upControl.className = "dashicons-before dashicons-arrow-up order";
	downControl.className = "dashicons-before dashicons-arrow-down order";
	container.className = "primary-input-order-controls-container inline-block";

	container.appendChild(upControl);
	container.appendChild(downControl);

	upControl.addEventListener('click', function() {
		that.parentList.moveInputUp(that.index);
	});
	
	downControl.addEventListener('click', function() {
		that.parentList.moveInputDown(that.index);
	});

	return container;
};

DesigninkMailchimpPrimaryInput.prototype.generateActiveControl = function() {
	var container = document.createElement('div');
	var activeControl = document.createElement('input');
	var that = this;

	activeControl.type = "checkbox";
	activeControl.setAttribute('checked', 'checked');

	container.className = "active-control-container inline-block";

	container.appendChild(activeControl);

	activeControl.addEventListener('click', function() {
		that.active = this.checked;
		that.parentList.updateCallback();
	});

	return container;
};

DesigninkMailchimpPrimaryInput.prototype.generateInputsControl = function() {
	var container = document.createElement('div');
	var labelControl = document.createElement('input');
	var nameControl = document.createElement('input');
	var labelControlContainer = document.createElement('div');
	var nameControlContainer = document.createElement('div');
	var that = this;

	labelControl.type = "text";
	labelControl.placeholder = "Option Label";
	labelControl.value = this.label;

	nameControl.type = "text";
	nameControl.placeholder = "Option Name";
	nameControl.value = this.name;

	container.className = "label-control-container inline-block";

	labelControlContainer.appendChild(labelControl);
	nameControlContainer.appendChild(nameControl);

	container.appendChild(labelControlContainer);
	container.appendChild(nameControlContainer);

	labelControl.addEventListener('keyup', function() {
		that.label = this.value;
		that.parentList.updateCallback();
	});

	nameControl.addEventListener('keyup', function() {
		that.name = this.value;
		that.parentList.updateCallback();
	});

	return container;
};