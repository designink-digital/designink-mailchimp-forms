/**
 * Group list
 */

function DesigninkMailchimpGroupList(updateCallback) {
	this.updateCallback = updateCallback;
	this.groups = [];
	this.container = document.getElementById('mc-group-options');
	this.addOptionButton = document.getElementById('mc-add-group-button');

	this.addOptionButton.addEventListener('click', this.addNewOption.bind(this));
}

DesigninkMailchimpGroupList.optionRegex = new RegExp(/<li><input.+name="group\[(\d+)\]\[(\d+)\]"[^>]+><label[^>]+>([a-zA-Z0-9\s\-]+)<\/label><\/li>/gi);
DesigninkMailchimpGroupList.nameRegex = new RegExp(/<strong>([a-zA-Z0-9\s:\-\/_]+)<\/strong>/ig);

DesigninkMailchimpGroupList.prototype.populateShortcodeArgs = function(args) {
	var groupKeys = Object.keys(this.groups);
	if(this.groups && groupKeys.length > 0) {
		var groupArgs = [];

		for(var i = 0; i < groupKeys.length; i++) {
			groupArgs.push(this.groups[groupKeys[i]].generateShortcodeArg());
		}

		args.groups = groupArgs.join(',');
	}

	return args;
};

DesigninkMailchimpGroupList.prototype.addNewOption = function() {
	this.groups.push(new DesigninkMailchimpGroup('', '', this));
	this.render();
};

DesigninkMailchimpGroupList.prototype.parseHtml = function(html) {
	var groupName = DesigninkMailchimpGroupList.nameRegex.exec(html);

	if(groupName) {
		groupName = groupName[1].trim()
		var match = DesigninkMailchimpGroupList.optionRegex.exec(html);
		var matches = [];

		if(Array.isArray(match)) {
			while(match != null) {
				var groupId = match[1];
				var optionId = match[2];
				var label = match[3].trim();

				var index = -1;
				for(var i = 0; i < matches.length; i++) {
					if(matches[i].id === groupId) {
						index = i;
						break;
					}
				}

				if(index === -1) {
					var Group = new DesigninkMailchimpGroup(groupId, groupName, this);
					matches.push(Group);
					index = +(matches.length - 1);
				} else if(!(matches[index] instanceof DesigninkMailchimpGroup)) {
					matches[index] = new DesigninkMailchimpGroup(groupId, groupName, this);
				}

				matches[index].addOption(new DesigninkMailchimpGroupOption(label, optionId, Group));
				match = DesigninkMailchimpGroupList.optionRegex.exec(html);
			}
		}

		DesigninkMailchimpGroupList.nameRegex.lastIndex = 0;
		DesigninkMailchimpGroupList.optionRegex.lastIndex = 0;

		return matches;
	}

	else {
		return [];
	}
};

DesigninkMailchimpGroupList.prototype.setGroups = function(groups = []) {
	for(var i = 0; i < groups.length; i++) {
		if(!(groups[i] instanceof DesigninkMailchimpGroup)) {
			console.error("Non-instance of DesigninkMailchimpGroup passed to DesigninkMailchimpGroupList::setGroup()");
			return;
		}
	}

	this.groups = groups;
};

DesigninkMailchimpGroupList.prototype.setGroupIndices = function() {
	for(var i = 0; i < this.groups.length; i++) {
		this.groups[i].setIndex(i);
	}
}

DesigninkMailchimpGroupList.prototype.render = function() {
	this.container.innerHTML = "";

	for(var i = 0; i < this.groups.length; i++) {
		this.container.appendChild(this.groups[i].generateElement());
	}

	for(var i = 0; i < this.groups.length; i++) {
		this.groups[i].setOptionIndices();
	}

	this.setGroupIndices();
	this.updateCallback();
};

DesigninkMailchimpGroupList.prototype.removeGroup = function(id) {
	for(var i = 0; i < this.groups.length; i++) {
		if(this.groups[i].id === id) {
			this.groups.splice(i, 1);
			this.render();
			break;
		}
	}
};	

/**
 * Group
 */

function DesigninkMailchimpGroup(id, name, parentList) {
	this.id = id;
	this.index = 0;
	this.name = name;
	this.options = [];
	this.parentList = parentList;
}

DesigninkMailchimpGroup.prototype.generateShortcodeArg = function() {
	var optionArguments = [];

	for(var i = 0; i < this.options.length; i++) {
		var argStr = this.options[i].id + ":" + this.options[i].label
		optionArguments.push(argStr);
	}

	argStr = this.id + ":" + this.name + "{" + optionArguments.join(',') + "}";

	return argStr;
};

DesigninkMailchimpGroup.prototype.setIndex = function(index) {
	this.index = index;
}

DesigninkMailchimpGroup.prototype.setOptionIndices = function() {
	for(var i = 0; i < this.options.length; i++) {
		this.options[i].setIndex(i);
	}
};

DesigninkMailchimpGroup.prototype.removeOption = function(index) {
	if(typeof index !== 'number') return;

	if(index < this.options.length && index >= 0) {
		this.options.splice(index, 1);
		this.parentList.render();
	}
};

DesigninkMailchimpGroup.prototype.moveOptionUp = function(index) {
	if(typeof index !== 'number') return;

	if(index > 0) {
		this.options.splice((index-1), 0, this.options.splice(index, 1)[0]);
		this.parentList.render();
	}
};

DesigninkMailchimpGroup.prototype.moveOptionDown = function(index) {
	if(typeof index !== 'number') return;

	if(index < this.options.length) {
		this.options.splice((index+1), 0, this.options.splice(index, 1)[0]);
		this.parentList.render();
	}
};

DesigninkMailchimpGroup.prototype.addOption = function(option) {
	if(option instanceof DesigninkMailchimpGroupOption) {
		this.options.push(option);
	} else {
		console.error("Non-instance of DesigninkMailchimpGroupOption passed to DesigninkMailchimpGroup::addOption()");
	}
};

DesigninkMailchimpGroup.prototype.generateElement = function() {
	var container = document.createElement('div');
	var inputs = document.createElement('table');

	inputs.appendChild(this.generateIdElement());
	inputs.appendChild(this.generateNameElement());
	container.appendChild(inputs);

	container.appendChild(this.generateRemoveElement());
	container.appendChild(this.generateOptionsElement());
	container.appendChild(this.generateNewOptionElement());

	container.classList.add('group');

	return container;
};

DesigninkMailchimpGroup.prototype.generateNewOptionElement = function() {
	var container = document.createElement('div');
	var button = document.createElement('input');
	var that = this;

	button.type = 'button';
	button.value = 'Add Option';
	button.className = 'button-primary';
	button.addEventListener('click', function() {
		that.addOption(new DesigninkMailchimpGroupOption('', '', that));
		that.parentList.render();
		that.parentList.updateCallback();
	});
	container.appendChild(button);

	return container;
};

DesigninkMailchimpGroup.prototype.generateIdElement = function() {
	var row = document.createElement('tr');
	var labelContainer = document.createElement('td');
	var inputContainer = document.createElement('td');

	var label = document.createElement('label');
	var input = document.createElement('input');
	var that = this;

	label.innerHTML = "Group ID:";
	input.value = this.id;
	input.type = 'text';

	labelContainer.appendChild(label);
	inputContainer.appendChild(input);
	row.appendChild(labelContainer);
	row.appendChild(inputContainer);

	input.addEventListener('keyup', function() {
		that.id = this.value;
		that.parentList.updateCallback();
	});

	return row;
};

DesigninkMailchimpGroup.prototype.generateNameElement = function() {
	var row = document.createElement('tr');
	var labelContainer = document.createElement('td');
	var inputContainer = document.createElement('td');

	var label = document.createElement('label');
	var input = document.createElement('input');
	var that = this;

	label.innerHTML = "Name ID:";
	input.value = this.name;
	input.type = 'text';

	labelContainer.appendChild(label);
	inputContainer.appendChild(input);
	row.appendChild(labelContainer);
	row.appendChild(inputContainer);

	input.addEventListener('keyup', function() {
		that.name = this.value;
		that.parentList.updateCallback();
	});

	return row;
};

DesigninkMailchimpGroup.prototype.generateRemoveElement = function() {
	var container = document.createElement('div');
	var removeControl = document.createElement('input');
	var that = this;

	removeControl.type = "button";
	removeControl.className = "button button-link-delete";
	removeControl.value = "Remove Group";
	container.className = "group-options-remove-control-container inline-block";

	container.appendChild(removeControl);

	removeControl.addEventListener('click', function() {
		that.parentList.removeGroup(that.id);
	});

	return container;
};

DesigninkMailchimpGroup.prototype.generateOptionsElement = function() {
	var container = document.createElement('ul');

	for(var i = 0; i < this.options.length; i++) {
		container.appendChild(this.options[i].element);
	}

	return container;
};

/**
 * Group option
 */

function DesigninkMailchimpGroupOption(label, id, parentGroup) {
	if(!(parentGroup instanceof DesigninkMailchimpGroup)) {
		console.error("Non-instance of DesigninkMailchimpGroup passed to DesigninkMailchimpGroupOption()");
		return;
	}

	this.parentGroup = parentGroup;
	this.label = label;
	this.id = id;
	this.index = 0;
	this.element = this.generateElement();
}

DesigninkMailchimpGroupOption.prototype.setIndex = function(index) {
	this.index = index;
};

DesigninkMailchimpGroupOption.prototype.generateElement = function() {
	var container = document.createElement('li');
	var inputs = document.createElement('table');
	
	container.appendChild(this.generateOrderControls());
	
	inputs.appendChild(this.generateIdElement());
	inputs.appendChild(this.generateNameElement());
	container.appendChild(inputs);

	container.appendChild(this.generateRemoveControl());

	container.className = "group-option";
	inputs.className = "inline-block";

	return container;
};

DesigninkMailchimpGroupOption.prototype.generateIdElement = function() {
	var row = document.createElement('tr');
	var labelContainer = document.createElement('td');
	var inputContainer = document.createElement('td');

	var label = document.createElement('label');
	var input = document.createElement('input');
	var that = this;

	label.innerHTML = "Option ID: ";
	input.value = this.id;
	input.type = 'text';

	labelContainer.appendChild(label);
	inputContainer.appendChild(input);
	row.appendChild(labelContainer);
	row.appendChild(inputContainer);

	input.addEventListener('keyup', function() {
		that.id = this.value;
		that.parentGroup.parentList.updateCallback();
	});

	return row;
};

DesigninkMailchimpGroupOption.prototype.generateNameElement = function() {
	var row = document.createElement('tr');
	var labelContainer = document.createElement('td');
	var inputContainer = document.createElement('td');

	var label = document.createElement('label');
	var input = document.createElement('input');
	var that = this;

	label.innerHTML = "Option Name: ";
	input.value = this.label;
	input.type = 'text';

	labelContainer.appendChild(label);
	inputContainer.appendChild(input);
	row.appendChild(labelContainer);
	row.appendChild(inputContainer);

	input.addEventListener('keyup', function() {
		that.label = this.value;
		that.parentGroup.parentList.updateCallback();
	});

	return row;
};

DesigninkMailchimpGroupOption.prototype.generateOrderControls = function() {
	var container = document.createElement('div');
	var upControl = document.createElement('i');
	var downControl = document.createElement('i');
	var that = this;

	upControl.className = "dashicons-before dashicons-arrow-up order";
	downControl.className = "dashicons-before dashicons-arrow-down order";
	container.className = "group-options-order-controls-container inline-block";

	container.appendChild(upControl);
	container.appendChild(downControl);

	upControl.addEventListener('click', function() {
		that.parentGroup.moveOptionUp(that.index);
	});
	
	downControl.addEventListener('click', function() {
		that.parentGroup.moveOptionDown(that.index);
	});

	return container;
};

DesigninkMailchimpGroupOption.prototype.generateRemoveControl = function() {
	var container = document.createElement('div');
	var removeControl = document.createElement('i');
	var that = this;

	removeControl.className = "dashicons-before dashicons-no remove";
	container.className = "group-options-remove-control-container inline-block";

	container.appendChild(removeControl);

	removeControl.addEventListener('click', function() {
		that.parentGroup.removeOption(that.index);
	});

	return container;
};