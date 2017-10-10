function showErrorAlert(title, content) {
    alert("[ERR " + title + "] " + content);
}

var LocalDB = function () {
    this.host = "host";
    this.dbname = "smsblast.db";
    this.db = null;
};

LocalDB.prototype.init = function () {
    var obj = this;
    var SQLite = window.cordova.require('cordova-sqlite-plugin.SQLite');
    this.db = new SQLite("smsblast");

    this.db.open(function (err) {
        if (err) {
            showErrorAlert("openDB", err);
        } else {
            obj.initTable();
        }
    });

};

LocalDB.prototype.checkIfTableExist = function (table_name, trueCallback, falseCallback) {
    var sql = "SELECT count(*) as cnt FROM sqlite_master WHERE type=? AND name=? ";
    this.db.query(sql, ["table", table_name], function (err, res) {
        if (!err) {
            if (res.rows.length > 0) {
                if (res.rows[0].cnt > 0) {
                    trueCallback();
                } else {
                    falseCallback();
                }
            } else {
                falseCallback();
            }

        } else {
            showErrorAlert("checkIfTableExist", err);
        }
    });
};

LocalDB.prototype.initTable = function () {

    var obj = this;

    var table_name = "contacts";
    var schema = "(id integer PRIMARY KEY, name text NOT NULL, phone_number text NOT NULL)";

    this.checkIfTableExist(table_name,
            function () { //true Callback
                alert("Table Exist");
            },
            function () { //false Callback
                alert("Table Does Not Exist");
                createTable(obj, table_name, schema);
            });

    function createTable(obj, table_name, schema) {
        var sql = "CREATE TABLE IF NOT EXISTS ";
        sql += " " + table_name;
        sql += " " + schema;

        this.db.query(sql, [], function (err, res) {
            if (err) {
                showErrorAlert("initTable", err);
            } else {
                obj.populateTable();
            }
        });
    }
};

LocalDB.prototype.dropTable = function (table_name) {
    this.db.query("DROP TABLE IF EXISTS ? "[table_name], function (err, res) {
        if (!err) {
        } else {
            showErrorAlert("dropTable", err);
        }
    });
};


LocalDB.prototype.populateTable = function () {
    var obj = this;
    var table_name = "contacts";
    var schema = "(id integer PRIMARY KEY, name text NOT NULL, phone_number text NOT NULL)";

    var sql = "INSERT INTO contacts (name,phone_number) VALUES (?,?)";
    var args = [];
    args.push(["Wan Zulsarhan", "0128775292"]);
    args.push(["John Doe", "01287556234"]);
    args.push(["Waida", "012875561235"]);

    for (var i in args) {
        var a = args[i];
        this.db.query(sql, a, function (err, res) {
            if (!err) {
                alert("Successfully inserted : " + a[0]);
            } else {
                showErrorAlert("populateTable", err);
            }
        });
    }


};


LocalDB.prototype.execQuery = function (sql, args, successCallback, errCallback) {
    this.db.query(sql, args, function (err, res) {
        if (!err) {
            successCallback(res);
        } else {
            errCallback(err);
        }
    });
};








