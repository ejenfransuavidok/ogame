'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.PopupBuilding = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogAjaxer = require('og.ajaxer.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PopupBuilding = exports.PopupBuilding = function () {
    function PopupBuilding() {
        _classCallCheck(this, PopupBuilding);

        this.ajaxer = new _ogAjaxer.Ajaxer();
        this.reject_building_button_handlers = {};
        if ($('.popup_building').length) {
            this.exec();
        }
    }

    _createClass(PopupBuilding, [{
        key: 'resources_building_callback',
        value: function resources_building_callback(id, event) {
            if (event) {
                this.start_continue_timer(id, event.event_begin, event.event_end, event.now);
            }
        }
    }, {
        key: 'start_continue_timer',
        value: function start_continue_timer(event_id, begin, end, now) {
            var _this = this;

            var evt_id = parseInt(event_id);
            var evt_begin = parseInt(begin);
            var evt_end = parseInt(end);
            var evt_now = parseInt(now);
            var rest = evt_end - evt_now;
            if (this.building_timer) {
                clearInterval(this.building_timer);
            }
            this.building_timer = setInterval(function () {
                if (rest <= -1) {
                    clearInterval(_this.building_timer);
                } else {
                    var interval = evt_end - evt_begin;
                    var hours = Math.floor(rest / 3600);
                    var minutes = Math.floor((rest - 3600 * hours) / 60);
                    var seconds = rest - 3600 * hours - 60 * minutes;
                    if (hours < 10) hours = "0" + hours;
                    if (minutes < 10) minutes = "0" + minutes;
                    if (seconds < 10) seconds = "0" + seconds;
                    var time = hours + ':' + minutes + ':' + seconds;
                    var progress = Math.ceil(100 - 100 * rest / interval);
                    $('[data-event_id=' + evt_id + ']').find('[data-entity=timer]').html(time);
                    $('[data-event_id=' + evt_id + ']').find('[data-entity_progress]').css('height', progress + '%');
                    rest--;
                }
            }, 1000);
        }
    }, {
        key: 'exec',
        value: function exec() {
            var _this2 = this;

            this.current_planet = $('[data-current_planet]').data('current_planet');
            $(document).on('click', '[data-entity=srcbuilding]', function (evt) {
                return _this2.src_building_select(evt);
            });
            $(document).on('click', '[data-entity=popup_building-popup__pp-popup__pp-control-build-button]', function (evt) {
                return _this2.build(evt);
            });
            $(document).on('mousedown', '[data-popup_id=popup_building]', function (evt) {
                var obj = $(evt.target).closest('.keep__item-preview');
                var tab = $(obj).data('tab_open');
                $('.popup_building').find('[data-building_tab]').removeClass('is-active');
                $('.popup_building').find('[data-building_tab=building_resources]').addClass('is-active');
            });
            $(document).on('mousedown', '[data-tab-group=building]', function (evt) {
                var obj = $(evt.target);
                var link = $(obj).data('tab-link');
                $('.popup_building').find('[data-building_tab]').removeClass('is-active');
                $('.popup_building').find('[data-building_tab=' + link + ']').addClass('is-active');
            });
            $(document).on('click', '[data-building_tab]', function (evt) {
                evt.preventDefault();
                var obj = $(evt.target).closest('.popup__head-item');
                var tab = $(obj).data('building_tab');
                $('[data-building_tab]').removeClass('is-active');
                $('[data-building_tab=' + tab + ']').addClass('is-active');
                $('[data-tab-link=' + tab + ']').trigger('click');
            });
        }
    }, {
        key: 'install_reject_button_handler',
        value: function install_reject_button_handler(event_id) {
            var _this3 = this;

            if (!(event_id in this.reject_building_button_handlers)) {
                $(document).on('click', '[data-entity=reject_building_button_' + event_id + ']', function (evt) {
                    _this3.ajaxer.execute('post', '/eventer/rejectbuilding', 'event_id=' + event_id, 'json', function (data) {
                        $.notify(data.message);
                    });
                });
                this.reject_building_button_handlers[event_id] = 1;
            }
        }
    }, {
        key: 'updatePlanetkeep',
        value: function updatePlanetkeep() {
            var _this4 = this;

            $.ajax({
                type: 'post',
                url: '/app/planetkeep',
                data: 'planetid=' + this.current_planet,
                dataType: "json",
                complete: function complete(res) {
                    try {
                        var data = res.responseJSON;
                        if (res.status) {
                            var status = res.status;
                            if (status == 200) {
                                if (data.result == 'YES') {
                                    $('[data-entity=game__planet-keep-keep]').html(data.content);
                                    $('.popup_building').remove();
                                    $(data.popup_building).insertAfter('.game');
                                    _this4.start_continue_timer(data.event_id, data.begin, data.end, data.now);
                                    _this4.install_reject_button_handler(data.event_id);
                                    window.theGame.plugs.update();
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
    }, {
        key: 'build',
        value: function build(evt) {
            var _this5 = this;

            var obj = $(evt.target);
            window.theGame.popup.close('.popup');
            $(obj).closest('.popup__pp').removeClass('is-open');
            this.building_id = $(obj).closest('.popup__pp').data('building_id');
            $.ajax({
                type: 'post',
                url: '/eventer/initbuild',
                data: 'planet=' + this.current_planet + '&buildingType=' + this.building_id,
                dataType: "json",
                complete: function complete(res) {
                    try {
                        var data = res.responseJSON;
                        if (res.status) {
                            var status = res.status;
                            if (status == 200) {
                                if (data.result == 'YES') {
                                    $.notify(data.message);
                                    _this5.updatePlanetkeep();
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
    }, {
        key: 'src_building_select',
        value: function src_building_select(evt) {
            var obj = $(evt.target).closest('.build__item');
            var id = $(obj).data('identifier');
            var period = $(obj).data('building_period');
            var level = $(obj).data('building_level');
            $('[data-entity=popup_building-popup__pp]').data('building_id', id);
            var title = $(obj).find('[data-entity=keep__item-text]').first().html();
            var description = $(obj).find('[data-entity=building-description]').first().html();
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-content-title]').html(title + '<br><span class="btn btn_lvl">lv.' + level + '</span>');
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-content-description]').html(description);
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-building-period]').html(period);
        }
    }]);

    return PopupBuilding;
}();