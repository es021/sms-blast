function setLocalStorage(key, data) {
    window.localStorage.setItem(key, JSON.stringify(data));
}

function getLocalStorage(key) {
    return JSON.parse(window.localStorage.getItem(key));
}

function deleteLocalStorage(key) {
    window.localStorage.removeItem(key);
}

var app = {
    ajax: new Ajax(),
    dom: new DOM(),
    view: new View(),
    service: new Service(),
    //app properties
    current_page: Page.HOME,
    // Application Constructor
    initWithCordova: function () {
        document.addEventListener('deviceready', this.onDeviceReady.bind(this), false);
    },
    initWithoutCordova: function () {
        this.onDeviceReady();
    },
    // Bind any cordova events here. Common events are:
    // 'pause', 'resume', etc.
    onDeviceReady: function () {
        this.ajax.init();
        this.dom.init();
        this.view.init();
    },
    isUserLoggedIn: function () {
        if (getLocalStorage(LocalStorage.USER.KEY) !== null) {
            return true;
        }

        return false;
    },
    logIn: function (user, jwt) {
        app.logged_in = true;
        setLocalStorage(LocalStorage.USER.KEY, user);
        setLocalStorage(LocalStorage.JWT, jwt);
        app.view.openPage(Page.HOME);

    },
    logOut: function () {
        deleteLocalStorage(LocalStorage.USER.KEY);
        deleteLocalStorage(LocalStorage.JWT);
        this.logged_in = false;
        this.view.openPage(Page.LOG_IN);
    }
};

//app.initWithCordova();
app.initWithoutCordova();
