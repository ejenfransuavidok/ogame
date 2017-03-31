'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Ajaxer = exports.Ajaxer = function () {
    function Ajaxer() {
        _classCallCheck(this, Ajaxer);
    }

    _createClass(Ajaxer, [{
        key: 'execute',
        value: function execute(_type, _url, _data, _dataType, callback) {
            $.ajax({
                type: _type,
                url: _url,
                data: _data,
                dataType: _dataType,
                complete: function complete(res) {
                    try {
                        var data = res.responseJSON;
                        if (res.status) {
                            var status = res.status;
                            if (status == 200) {
                                if (data.result == 'YES') {
                                    callback(data);
                                } else {
                                    $.notify(data.message);
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
    }]);

    return Ajaxer;
}();