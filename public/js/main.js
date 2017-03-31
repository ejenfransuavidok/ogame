'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogUniverse = require('og.universe.js');

var _ogAuth = require('og.auth.js');

var _ogBuildings = require('og.buildings.js');

var _ogSources_updater = require('og.sources_updater.js');

var _ogPopup_building = require('og.popup_building.js');

var _ogDb_eventer = require('og.db_eventer.js');

var _ogFleet_ = require('og.fleet_1.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var JQueryStarter = function () {
    function JQueryStarter() {
        _classCallCheck(this, JQueryStarter);
    }

    _createClass(JQueryStarter, [{
        key: 'run',
        value: function run(callback) {
            var _this = this;

            this.ali = setInterval(function () {
                if (typeof jQuery !== 'function') return;
                clearInterval(_this.ali);
                callback();
            }, 50);
        }
    }]);

    return JQueryStarter;
}();

var Main = function () {
    function Main() {
        var _this2 = this;

        _classCallCheck(this, Main);

        this.runner = new JQueryStarter();
        this.runner.run(function () {
            try {
                console.log('main loaded');
                _this2.processing();
            } catch (err) {
                console.log(err.message);
            }
        });
    }

    _createClass(Main, [{
        key: 'processing',
        value: function processing() {
            this.universe = new _ogUniverse.Universe();
            this.auth = new _ogAuth.Auth();
            //this.buildings = new Buildings();
            this.source_updater = new _ogSources_updater.SrcUpdater();
            this.popup_building_handler = new _ogPopup_building.PopupBuilding();
            this.popup_fleet_1_handler = new _ogFleet_.Fleet_1();

            this.dbeventer = new _ogDb_eventer.DBEventer(this.popup_building_handler);
        }
    }]);

    return Main;
}();

var main = new Main();