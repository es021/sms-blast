
var Ajax = function () {

};

Ajax.prototype.init = function () {
    jQuery(document).ready(function () {
        console.log("init");
        jQuery.extend(jQuery.tmpl.tag, {
            "var": {
                open: "var $1;"
            }
        });
    });
};
