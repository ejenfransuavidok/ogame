'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.Auth = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogValidators = require('og.validators.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Auth = exports.Auth = function () {
    function Auth() {
        var _this = this;

        _classCallCheck(this, Auth);

        this.theGame = window.theGame;

        $(document).on('click', '.js-submit-form', function (evt) {
            evt.preventDefault();
            $(evt.target).closest('form').submit();
        });

        $(document).on('submit', '.game__login-form', function (evt) {
            return _this.form_submit(evt);
        });
        $(document).on('click', '[data-menu_item=logout]', function (evt) {
            return _this.logout(evt);
        });
    }

    _createClass(Auth, [{
        key: 'logout',
        value: function logout(evt) {
            evt.preventDefault();
            $.ajax({
                type: 'post',
                url: '/dologout',
                dataType: "json",
                complete: function complete(res) {
                    try {
                        var data = res.responseJSON;
                        if (res.status) {
                            var status = res.status;
                            if (status == 200) {
                                if (data.result.result == 'error') {
                                    $.notify(data.result.message, 'error');
                                } else {
                                    $.notify(data.result.message, 'message');
                                    if (typeof data.result.redirect != 'undefined') window.location.href = data.result.redirect;
                                }
                            } else {
                                $.notify('Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!');
                            }
                        } else {
                            $.notify('Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!');
                        }
                    } catch (err) {
                        $.notify(err.message);
                    }
                }
            });
        }
    }, {
        key: 'form_submit',
        value: function form_submit(evt) {
            evt.preventDefault();
            var form = $(evt.target);
            var action = $(form).prop('action');
            if (window.theGame.checkForm(form)) {
                $.ajax({
                    type: 'post',
                    url: action,
                    data: $(form).serialize(),
                    dataType: "json",
                    complete: function complete(res) {
                        try {
                            var data = res.responseJSON;
                            if (res.status) {
                                var status = res.status;
                                if (status == 200) {
                                    if (data.result.result == 'error') {
                                        $.notify(data.result.message, 'error');
                                    } else {
                                        $.notify(data.result.message, 'message');
                                        if (typeof data.result.redirect != 'undefined') {
                                            window.location.href = data.result.redirect;
                                        }
                                    }
                                } else {
                                    $.notify('Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!');
                                }
                            } else {
                                $.notify('Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!');
                            }
                        } catch (err) {
                            $.notify(err.message);
                        }
                        return 0;
                    }
                });
            }
            return 0;
        }
    }]);

    return Auth;
}();