'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Fleet_1 = exports.Fleet_1 = function () {
    function Fleet_1() {
        _classCallCheck(this, Fleet_1);

        if ($('.popup_fleet_1').length) {
            this.exec();
        }
    }

    _createClass(Fleet_1, [{
        key: 'exec',
        value: function exec() {}
    }]);

    return Fleet_1;
}();