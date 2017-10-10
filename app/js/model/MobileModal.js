var MobileModal = function (modal_dom) {
    this.my_modal = modal_dom;
    this.modal_header = this.my_modal.find(".my_modal_header");
    this.modal_footer = this.my_modal.find(".my_modal_footer");
    this.modal_close = this.my_modal.find("#my_modal_close");

    this.registerDomEvent();
};

MobileModal.prototype.registerDomEvent = function () {
    var obj = this;
    this.modal_close.click(function () {
        obj.close();
    });
};

MobileModal.prototype.open = function () {
    app.dom.main_page.hide();
    var obj = this;
    this.modal_header.css("position", "relative");
    this.modal_footer.css("position", "relative");
    this.my_modal.css("top", this.my_modal.css("height"));
    this.my_modal.animate({
        top: 0
    }, 200, function () {
        obj.modal_header.css("position", "fixed");
        obj.modal_footer.css("position", "fixed");
    });
};

MobileModal.prototype.close = function () {
    app.dom.main_page.show();
    var obj = this;
    this.modal_header.css("position", "relative");
    this.modal_footer.css("position", "relative");

    this.my_modal.animate({
        top: this.my_modal.css("height")
    }, 200, function () {
        obj.my_modal.remove();
    });
};