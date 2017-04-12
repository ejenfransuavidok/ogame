'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var GlobalVars = exports.GlobalVars = function () {
    function GlobalVars() {
        _classCallCheck(this, GlobalVars);

        if ($('[data-entity_user]').length) this.current_user = $('[data-entity_user]').data('entity_user');else this.current_user = 0;
        if ($('[data-entity_planet]').length) this.current_planet = $('[data-entity_planet]').data('entity_planet');else this.current_planet = 0;
    }

    _createClass(GlobalVars, [{
        key: 'getCurrentPlanet',
        value: function getCurrentPlanet() {
            if (!this.current_planet) {
                throw 'Current Planet did not define in that page';
            } else {
                return this.current_planet;
            }
        }
    }, {
        key: 'getCurrentUser',
        value: function getCurrentUser() {
            if (!this.current_user) {
                throw 'Current User did not define in that page';
            } else {
                return this.current_user;
            }
        }
    }]);

    return GlobalVars;
}();