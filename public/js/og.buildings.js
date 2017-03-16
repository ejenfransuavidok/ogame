'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Buildings = exports.Buildings = function () {
    function Buildings() {
        var _this = this;

        _classCallCheck(this, Buildings);

        if ($('[data-building_id]').length) {
            this.timer = setInterval(function () {
                _this.updater();
            }, 10000);
        }
    }

    _createClass(Buildings, [{
        key: 'updater',
        value: function updater() {
            $.ajax({
                type: 'post',
                url: '/flight/update',
                data: '',
                dataType: "json",
                complete: function complete(res) {
                    try {
                        (function () {
                            var data = res.responseJSON;
                            if (res.status) {
                                var status = res.status;
                                if (status == 200) {
                                    if (data.result.result == 'ERROR') {
                                        $.notify('Пользователь не авторизован', 'ERROR');
                                    } else if (data.result.result == 'TIME') {
                                        $.notify('Время обновления не пришло', 'message');
                                    } else {
                                        $.notify('Данные пришли', 'message');
                                        //console.log(data.result);

                                        var _loop = function _loop(building_id) {
                                            var tds = $('[data-building_id=' + building_id + ']');
                                            tds.each(function (idx, elm) {
                                                for (var name in data.result[building_id]) {
                                                    if ($(elm).data('type') == name) {
                                                        $(elm).text(data.result[building_id][name]);
                                                    }
                                                }
                                            });
                                        };

                                        for (var building_id in data.result) {
                                            _loop(building_id);
                                        }
                                    }
                                } else {
                                    $.notify('Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!');
                                }
                            } else {
                                $.notify('Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!');
                            }
                        })();
                    } catch (err) {
                        $.notify(err.message);
                    }
                }
            });
        }
    }]);

    return Buildings;
}();