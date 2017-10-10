String.prototype.ucfirst = function () {
    var str = this;
    var firstLetter = str.substr(0, 1);
    return firstLetter.toUpperCase() + str.substr(1);
};

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

String.prototype.htmlToInput = function () {
    var toRet = this.replaceAll('<br>', '\n');
    return toRet;
};
