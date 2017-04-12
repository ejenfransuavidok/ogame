'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.PopupBuilding = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogAjaxer = require('og.ajaxer.js');

var _ogGlobal_vars = require('og.global_vars.js');

var _ogInterval = require('og.interval.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PopupBuilding = exports.PopupBuilding = function () {
    function PopupBuilding(socket) {
        _classCallCheck(this, PopupBuilding);

        this.socket = socket;
        this.ajaxer = new _ogAjaxer.Ajaxer();
        this.interval = new _ogInterval.Interval();
        this.src_buildind_block_checking('[data-entity=resources-build-block-main]');
        //this.reject_building_button_handlers = {};
        if ($('.popup_building').length) {
            this.exec();
        }
        socket.on('sourceBuildingComplete', function (data) {
            $.notify(data.message);
        });
    }

    _createClass(PopupBuilding, [{
        key: 'resources_building_callback',
        value: function resources_building_callback(id, event) {
            //if(event){
            //    this.start_continue_timer(id, event.event_begin, event.event_end, event.now);
            //}
        }
    }, {
        key: 'start_continue_timer',
        value: function start_continue_timer(event_id, begin, end, now, building_id, main) {
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
                    var current_level = parseInt($('[data-identifier=' + building_id + ']').attr('data-building_level'));
                    var price_factor = parseFloat($('[data-identifier=' + building_id + ']').attr('data-building_price_factor'));
                    var new_period = parseInt(Math.pow(parseFloat($('[data-identifier=' + building_id + ']').attr('data-building_price_factor')), current_level) * parseInt($('[data-identifier=' + building_id + ']').attr('data-summ_resource_consume')) / 30);
                    $('[data-identifier=' + building_id + ']').attr('data-building_period', _this.interval.interval2string(new_period));
                    $('[data-identifier=' + building_id + ']').attr('data-building_level', parseInt(current_level + 1));
                    $('[data-identifier=' + building_id + ']').find('.keep__item-lvl').html('<span>lv. ' + parseInt(current_level + 1) + '</span>');
                    $('[data-event_id=' + evt_id + ']').find('[data-entity_progress]').css('height', '0%');
                    $(main).attr('data-event_id', 0);
                    /**
                     * @ запрос на обновление ивентов
                     */
                    _this.ajaxer.execute('post', '/cron/updater/index', '', 'json', function (data) {
                        /**
                         * @ обновим экран
                         */
                        console.log(data);
                        $.notify(data.message);
                    });
                } else {
                    var interval = evt_end - evt_begin;
                    var time = _this.interval.resttime2string(rest, evt_begin, evt_end);
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
            $(document).on('click', '[data-entity=reject_building_button]', function (evt) {
                return _this2.reject_building(evt);
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
        key: 'reject_building',
        value: function reject_building(evt) {
            var obj = $(evt.target);
            var root = $(obj).closest('[data-root=root]');
            var event_id = parseInt($(root).attr('data-event_id'));
            if (event_id) {
                $.notify('building going on');
            } else {
                $.notify('building isnt building');
            }
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

            this.ajaxer.execute('post', '/app/planetkeep', 'planetid=' + this.current_planet, 'json', function (data) {
                $('[data-entity=game__planet-keep-keep]').html(data.content);
                $('.popup_building').remove();
                $(data.popup_building).insertAfter('.game');
                _this4.start_continue_timer(data.event_id, data.begin, data.end, data.now);
                _this4.install_reject_button_handler(data.event_id);
                window.theGame.plugs.update();
            });
        }
    }, {
        key: 'build',
        value: function build(evt) {
            var _this5 = this;

            /**
             * @ запуск строительства
             */
            var obj = $(evt.target);
            window.theGame.popup.close('.popup');
            $(obj).closest('.popup__pp').removeClass('is-open');
            this.building_id = $(obj).closest('.popup__pp').data('building_id');
            this.ajaxer.execute('post', '/eventer/initbuild', 'planet=' + this.current_planet + '&buildingType=' + this.building_id, 'json', function (data) {
                var main = $('[data-entity=resources-build-block-main]');
                $(main).attr('data-event_id', data.event_id);
                _this5.start_continue_timer(data.event_id, data.begin, data.end, data.now, _this5.building_id, main);
            });
        }
    }, {
        key: 'src_buildind_block_checking',
        value: function src_buildind_block_checking(selector) {
            if ($(selector).length) {
                var main = $(selector);
                if (typeof $(main).attr('data-event_id') != 'undefined' && $(main).attr('data-event_id') != 0 && $(main).attr('data-event_id') != '') {
                    var event_id = parseInt($(main).attr('data-event_id'));
                    var begin = parseInt($(main).attr('data-event_begin'));
                    var end = parseInt($(main).attr('data-event_end'));
                    var now = parseInt($(main).attr('data-event_now'));
                    var building_id = parseInt($(main).attr('data-identifier'));
                    this.start_continue_timer(event_id, begin, end, now, building_id);
                }
            }
        }
    }, {
        key: 'src_building_select',
        value: function src_building_select(evt) {
            var obj = $(evt.target).closest('.build__item');
            var id = $(obj).attr('data-identifier');
            var period = $(obj).attr('data-building_period');
            var level = $(obj).attr('data-building_level');
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