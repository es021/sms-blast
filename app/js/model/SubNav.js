
var SubNav = function (id, active, eventHandler) {
    this.dom = jQuery("#" + id);
    this.items = this.dom.find(".sub_nav_item");
    this.eventHandler = eventHandler;

    this.init();
    this.eventHandler();

    var currentActiveDom = this.dom.find("#" + active);
    currentActiveDom.addClass("active");
    this.active = active;

};

SubNav.prototype.init = function () {
    //set active 
    //get width of sub items
    var width = 100 / this.items.length;
    this.items.css("width", width + "%");

    //position under header
    this.dom.css("top", app.dom.header.css("height"));
    this.dom.css("visibility", "visible");
    var padding = "<div style='height : 40px'></div>";
    app.dom.content.prepend(padding);
};


