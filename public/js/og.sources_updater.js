'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SrcUpdater = exports.SrcUpdater = function () {
    function SrcUpdater() {
        var _this = this;

        _classCallCheck(this, SrcUpdater);

        if ($('[data-resources]').length) {
            this.current_planet = $('[data-current_planet]').data('current_planet');
            this.timer = setInterval(function () {
                _this.updater();
            }, 10000);
        }
    }

    _createClass(SrcUpdater, [{
        key: 'updater',
        value: function updater() {
            $.ajax({
                type: 'post',
                url: '/cron/updater/srcupdater',
                data: 'current_planet=' + this.current_planet,
                dataType: "json",
                complete: function complete(res) {
                    try {
                        var data = res.responseJSON;
                        if (res.status) {
                            var status = res.status;
                            if (status == 200) {
                                if (data.result == 'YES') {
                                    var values = data.values;
                                    if (Object.keys(values).length) {
                                        for (var key in values) {
                                            $('[data-resources=' + key + ']').text(values[key]);
                                        }
                                    }
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

    return SrcUpdater;
}();