'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var DBEventer = exports.DBEventer = function () {
    function DBEventer(popup_building_handler) {
        var _this = this;

        _classCallCheck(this, DBEventer);

        this.popup_building_handler = popup_building_handler;
        if ($('[data-current_planet]').length) {
            this.current_planet = $('[data-current_planet]').data('current_planet');
            this.timer = setInterval(function () {
                _this.querysender();
            }, 10000);
        }
    }

    _createClass(DBEventer, [{
        key: 'is_event_type_in_events_list',
        value: function is_event_type_in_events_list(event_type, data) {
            if (!data) return false;else {
                for (var id in data) {
                    var event = data[id];
                    var e_t = event.event_type;
                    if (e_t == event_type) return true;
                }
            }
            return false;
        }
    }, {
        key: 'querysender',
        value: function querysender() {
            var _this2 = this;

            $.ajax({
                type: 'post',
                url: '/eventer/jsreader',
                data: 'planetid=' + this.current_planet,
                dataType: "json",
                complete: function complete(res) {
                    try {
                        var data = res.responseJSON;
                        if (res.status) {
                            var status = res.status;
                            if (status == 200) {
                                if (data.result == 'YES') {
                                    if (data.content) {
                                        for (var id in data.content) {
                                            var event = data.content[id];
                                            var event_type = event.event_type;
                                            if (event_type == 2) _this2.popup_building_handler.resources_building_callback(id, event);
                                        }
                                        if (!_this2.is_event_type_in_events_list(2, data.content))
                                            /**
                                             * строительство ресурсного здания закончено, проверим - осталась ли картинка
                                             */
                                            if ($('[data-entity=source-building-process]').length) _this2.popup_building_handler.updatePlanetkeep();
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

    return DBEventer;
}();