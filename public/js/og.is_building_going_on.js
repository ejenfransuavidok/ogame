'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var IsBuildingGoingOn = exports.IsBuildingGoingOn = function () {
    function IsBuildingGoingOn() {
        _classCallCheck(this, IsBuildingGoingOn);
    }

    _createClass(IsBuildingGoingOn, [{
        key: 'check',
        value: function check(selector) {
            var main = $(selector);
            var result = $(main).length && typeof $(main).attr('data-event_id') != 'undefined' && $(main).attr('data-event_id') != 0 && $(main).attr('data-event_id') != '';

            //console.log('result = ' + result);

            return result;
            /*( 
            $(main).length &&
            typeof $(main).attr('data-event_id') != 'undefined' && 
            $(main).attr('data-event_id') != 0 && 
            $(main).attr('data-event_id') != ''
            );
            */
        }
    }]);

    return IsBuildingGoingOn;
}();