var DOM = function () {
    this.footer_icon = null;

    this.content = null;
    this.header = null;
    this.footer = null;
    this.footer_logged_in = null;
    this.footer_logged_out = null;
    this.main_page = null;

    //this.contentTemplate = null;
    this.headerTemplate = null;
    //this.footerTemplate = null;
};

DOM.prototype.init = function () {
    this.initDom();
    this.registerEvent();
};

DOM.prototype.updateFooter = function (page_id) {
    jQuery("i#" + app.current_page).removeClass("active");
    jQuery("i#" + page_id).addClass("active");

    this.toogleLogInOut(this.footer_logged_in, this.footer_logged_out);
};

DOM.prototype.toogleLogInOut = function (log_in, log_out) {
    if (app.isUserLoggedIn()) {
        log_in.show();
        log_out.hide();
    } else {
        log_in.hide();
        log_out.show();
    }
};

DOM.prototype.initDom = function () {
    this.footer_icon = jQuery(".page #footer i.fa");

    this.main_page = jQuery("#main_page");
    this.content = jQuery(".page #content");
    this.header = jQuery(".page #header");
    this.footer = jQuery(".page #footer");
    this.footer_logged_in = this.footer.find(".logged_in");
    this.footer_logged_out = this.footer.find(".logged_out");

    //this.contentTemplate = jQuery("#contentTemplate");
    this.headerTemplate = jQuery("#headerTemplate");
    this.footerTemplate = jQuery("#footerTemplate");
};

DOM.prototype.registerEvent = function () {
    this.footer_icon.click(function () {
        var cur = jQuery(this);
        var page_id = cur.attr("id")
        if (page_id !== app.current_page) {
            app.view.openPage(page_id);
        }
    });
};

DOM.prototype.toggleShowHideDom = function (dom1, dom2) {
    if (dom1.attr("hidden") === "hidden") {
        dom1.removeAttr("hidden");
        if (dom2 !== undefined) {
            dom2.attr("hidden", "hidden");
        }
    } else {
        dom1.attr("hidden", "hidden");
        if (dom2 !== undefined) {
            dom2.removeAttr("hidden");
        }
    }
};
