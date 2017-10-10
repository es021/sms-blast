var View = function () {
};

View.prototype.init = function () {

    console.log(app.current_page);

    if (app.isUserLoggedIn()) {
        var tempCurPage = getLocalStorage(LocalStorage.CURRENT_PAGE);
        if (tempCurPage !== null) {
            app.current_page = tempCurPage;
        }

        this.openPage(app.current_page);
    } else {
        this.openPage(Page.LOG_IN);
    }
    //this.setHeaderFooter();
};

/*
 View.prototype.setHeaderFooter = function () {
 jQuery(function () {
 jQuery(window).scroll(stickyRelocate);
 stickyRelocate();
 });
 
 function stickyRelocate() {
 var window_top = jQuery(window).scrollTop();
 app.dom.header.css("top", window_top + "px");
 app.dom.footer.css("bottom", "-" + window_top + "px");
 //console.log(window_top);
 }
 };
 */

View.prototype.openPage = function (page_id, successHandler) {

    //app.redirectIfNotLogin();

    var obj = this;
    var data = {};

    switch (page_id) {
        case Page.HOME:
            data.title = "SMS Blast";
            break;
        case Page.LOG_IN:
            data.title = "SMS Blast";
            break;
        case Page.SEND_MESSAGE:
            data.title = "Send Message";
            break;
        case Page.CONTACTS:
            data.title = "Manage Contacts";
            break;
            break;
        case Page.DRAFT_SENT:
            data.title = "Sent & Draft";
            break;
        default :
            data.title = "Settings";
            break;
    }

    jQuery.get("view/" + page_id + ".html?v=" + HTML_VERSION, function (content) {
        data.content = content;
        obj.renderPage(page_id, data);

        if (typeof successHandler !== "undefined") {
            successHandler();
        }

    }).fail(function () {
        data.content = "Page Not Found";
        obj.renderPage(page_id, data);
    });

};

View.prototype.openModal = function (modal_title, modal_body, eventRegister) {
    var body = jQuery("body");

    jQuery.get("view/template/modal.html?v=" + HTML_VERSION, function (modal) {
        modal = modal.replace("{modal_title}", modal_title);
        modal = modal.replace("{modal_body}", modal_body);
        body.append(modal);

        if (typeof eventRegister !== "undefined") {
            eventRegister();
        }

    }).fail(function () {
        console.log("Failed to open modal");
    });
};

View.prototype.renderPage = function (page_id, data) {

    //using jquery template
    var header_view = app.dom.headerTemplate.tmpl([{page_id: page_id, title: data.title}]);
    app.dom.header.html(header_view);

    app.dom.content.html(data.content);

    app.dom.updateFooter(page_id);

    //set the current page
    app.current_page = page_id;
    setLocalStorage(LocalStorage.CURRENT_PAGE, page_id);
};




