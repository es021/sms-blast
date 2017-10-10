/*
 * NOTE .....
 * 
 * check the URL path 
 * http://localhost/SMSBlastWebService/
 * http://localhost/SMSBlastWebService
 * is not the same.
 * 
 * The .htaccess on the server read this as a 301 GET 
 * redirect to the correct URL and deleted the POST data. 
 *  The simple solution was to double check the url path.
 * 
 */

var Service = function () {
    this.url = SERVICE_URL;
};

Service.prototype.sendPostRequest = function (action, data, successHandler, errorHandler) {

    var param = {};
    param["action"] = action;
    param["data"] = data;

    var JWT = "";
    if (action !== "login") {
        JWT = getLocalStorage(LocalStorage.JWT);
    }

    jQuery.ajax({
        type: "POST",
        beforeSend: function (request) {
            request.setRequestHeader("Authorization", JWT);
        },
        url: this.url,
        data: param,
        dataType: "json",
        success: successHandler,
        error: errorHandler
    });

};