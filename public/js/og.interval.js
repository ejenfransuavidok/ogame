"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Interval = exports.Interval = function () {
    function Interval() {
        _classCallCheck(this, Interval);
    }

    _createClass(Interval, [{
        key: "resttime2string",
        value: function resttime2string(rest, begin, end) {
            var interval = end - begin;
            var hours = Math.floor(rest / 3600);
            var minutes = Math.floor((rest - 3600 * hours) / 60);
            var seconds = rest - 3600 * hours - 60 * minutes;
            if (hours < 10) hours = "0" + hours;
            if (minutes < 10) minutes = "0" + minutes;
            if (seconds < 10) seconds = "0" + seconds;
            var time = hours + ':' + minutes + ':' + seconds;
            return time;
        }
    }, {
        key: "interval2string",
        value: function interval2string(interval) {
            var hours = Math.floor(interval / 3600);
            var minutes = Math.floor((interval - 3600 * hours) / 60);
            var seconds = interval - 3600 * hours - 60 * minutes;
            if (hours < 10) hours = "0" + hours;
            if (minutes < 10) minutes = "0" + minutes;
            if (seconds < 10) seconds = "0" + seconds;
            var time = hours + ':' + minutes + ':' + seconds;
            return time;
        }
    }]);

    return Interval;
}();