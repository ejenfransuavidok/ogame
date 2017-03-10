'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Auth = exports.Auth = function Auth() {
    _classCallCheck(this, Auth);

    if ($('.game__login').length) {
        $.notify('login loaded');
    }
};