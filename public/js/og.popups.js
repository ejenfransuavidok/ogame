"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Popups = exports.Popups = function () {
    function Popups() {
        /*
        if($('[data-popup_id=popup_building]').length) {
            $(document).on('mousedown', '[data-popup_id=popup_building]', (evt) => this.popup_building(evt));
        }
        */

        _classCallCheck(this, Popups);
    }

    _createClass(Popups, [{
        key: "popup_building",
        value: function popup_building(evt) {
            /*
            let obj = $(evt.target).closest('[data-popup_tab]');
            let tabname = $(obj).data('popup_tab');
            $('[data-building_tab]').removeClass('is-active');
            $('[data-building_tab='+tabname+']').addClass('is-active');
            */
        }
    }]);

    return Popups;
}();