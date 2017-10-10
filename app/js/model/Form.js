var Form = function (form_dom, rules, submitHandler, form_error, loading_dom, inputField) {
    this.current_form = form_dom;
    this.initial_form = form_dom.clone();
    this.rules = rules;
    this.submitHandler = submitHandler;
    this.inputField = inputField;
    this.form_error = form_error;
    this.loading_dom = loading_dom;

    this.init();
};

Form.prototype.resetForm = function () {
    this.initial_form.find("input, textarea").val("");
    this.current_form.find("input, textarea").val("");
};

Form.prototype.initEdit = function () {
    this.initial_form = this.current_form.clone();
};

Form.prototype.init = function () {
    var obj = this;
    jQuery(document).ready(function () {
        obj.current_form.validate({
            rules: obj.rules,
            submitHandler: obj.submitHandler
        });

        obj.registerEvent();
    });
};

Form.prototype.registerEvent = function () {
    var obj = this;

    if (this.inputField !== null) {
        this.inputField.keypress(function (e) {
            if (e.which === 13) {
                obj.current_form.submit();
                return false;
            }
        });
    }
};

Form.prototype.getUpdateData = function () {
    var init = this.getInitalObjectData();
    var cur = this.getCurrentObjectData();

    var newData = {};
    var count = false;
    for (var i in cur) {
        if (init[i] !== cur[i]) {
            count = true;
            newData[i] = cur[i];
        }
    }

    if (!count) {
        return false;
    }

    return newData;
};

Form.prototype.startSubmit = function () {
    this.form_error.html("");
    app.dom.toggleShowHideDom(this.loading_dom, this.current_form);
};

Form.prototype.finishSubmit = function (failed) {
    if (typeof failed === undefined) {
        this.initial_form = this.current_form;
    }
    app.dom.toggleShowHideDom(this.loading_dom, this.current_form);
};

Form.prototype.getInitalObjectData = function () {
    return this.getObjectFromFormData(this.initial_form);
};

Form.prototype.getCurrentObjectData = function () {
    return this.getObjectFromFormData(this.current_form);
};

Form.prototype.getObjectFromFormData = function (form) {
    var temp = form.serializeArray();
    var toReturn = {};
    for (var i in temp) {
        toReturn[temp[i]["name"]] = temp[i]["value"];
    }
    return toReturn;
};
