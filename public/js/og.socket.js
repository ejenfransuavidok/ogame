'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.OGSocket = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogGlobal_vars = require('og.global_vars.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var OGSocket = exports.OGSocket = function () {
    function OGSocket(socket) {
        _classCallCheck(this, OGSocket);

        this.socket = socket;
        this.gvars = new _ogGlobal_vars.GlobalVars();
        this.set_requests_listeners();
    }

    _createClass(OGSocket, [{
        key: 'set_requests_listeners',
        value: function set_requests_listeners() {
            var _this = this;

            this.socket.on('get_current_planet_user', function (data) {
                /**
                 * @ запрос на глобальные данные
                 */
                _this.send_current_planet_user();
            });
        }
    }, {
        key: 'send_current_planet_user',
        value: function send_current_planet_user() {
            this.socket.emit('set_current_planet_user', { currentUser: this.gvars.getCurrentUser(), currentPlanet: this.gvars.getCurrentPlanet() });
        }
    }]);

    return OGSocket;
}();