'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.PopupBuilding = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogAjaxer = require('og.ajaxer.js');

var _ogGlobal_vars = require('og.global_vars.js');

var _ogInterval = require('og.interval.js');

var _ogIs_building_going_on = require('og.is_building_going_on.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PopupBuilding = exports.PopupBuilding = function () {
    function PopupBuilding(socket) {
        _classCallCheck(this, PopupBuilding);

        this.colors = []; //['clr_yellow', 'clr_green', 'clr_brown', 'clr_red', 'clr_red'];
        this.socket = socket;
        this.ajaxer = new _ogAjaxer.Ajaxer();
        this.interval = new _ogInterval.Interval();
        this.is_building_going_on = new _ogIs_building_going_on.IsBuildingGoingOn();
        this.building_timer = [];
        this.buildind_block_checking('[data-entity=resources-build-block-main]');
        this.buildind_block_checking('[data-entity=industrial-build-block-main]');
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
            /**
             * @ таймер на элемент
             */
            var element_timer = $('[data-item_identifier=' + building_id + ']').find('.keep__item-preview').next();
            if (!$(element_timer).hasClass('keep__item-timer')) {
                $('[data-item_identifier=' + building_id + ']').find('.keep__item-preview').after("<div class='keep__item-timer'></div>");
            }
            element_timer = $('[data-item_identifier=' + building_id + ']').find('.keep__item-preview').next();
            /**
             * 
             */
            if (this.building_timer[event_id]) {
                clearInterval(this.building_timer[event_id]);
            }
            this.building_timer[event_id] = setInterval(function () {
                if (rest <= -1) {
                    clearInterval(_this.building_timer[event_id]);
                    var current_level = parseInt($('[data-item_identifier=' + building_id + ']').attr('data-building_level'));
                    var price_factor = parseFloat($('[data-item_identifier=' + building_id + ']').attr('data-building_price_factor'));
                    var new_period = parseInt(Math.pow(parseFloat($('[data-item_identifier=' + building_id + ']').attr('data-building_price_factor')), current_level) * parseInt($('[data-item_identifier=' + building_id + ']').attr('data-summ_resource_consume')) / 30);
                    $('[data-item_identifier=' + building_id + ']').attr('data-building_period', _this.interval.interval2string(new_period));
                    $('[data-item_identifier=' + building_id + ']').attr('data-building_level', parseInt(current_level + 1));
                    $('[data-item_identifier=' + building_id + ']').find('.keep__item-lvl').html('<span>lv. ' + parseInt(current_level + 1) + '</span>');
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
                        _this.restore_context(main);
                    });
                } else {
                    var interval = evt_end - evt_begin;
                    var time = _this.interval.resttime2string(rest, evt_begin, evt_end);
                    var progress = Math.ceil(100 - 100 * rest / interval);
                    $('[data-event_id=' + evt_id + ']').find('[data-entity=timer]').html(time);
                    $('[data-event_id=' + evt_id + ']').find('[data-entity_progress]').css('height', progress + '%');
                    /**
                     * таймер на элемент
                     */
                    var timer_content = $('[data-event_id=' + evt_id + ']').find('.keep__item-timer').clone(true).html();
                    $(element_timer).html(timer_content);
                    /**
                     * 
                     */
                    rest--;
                }
            }, 1000);
        }
    }, {
        key: 'exec',
        value: function exec() {
            var _this2 = this;

            this.current_planet = $('[data-current_planet]').data('current_planet');
            $(document).on('click', '[data-entity=srcbuilding], [data-entity=industrialbuilding]', function (evt) {
                return _this2.building_select(evt);
            });
            /**
             * здесь строительство только для ресурсного и индустриального здания !!! (остальные пока не предусмотрены!!!)
             */
            $(document).on('click', '[data-entity=popup_building-popup__pp-popup__pp-control-build-button]', function (evt) {
                return _this2.build(evt);
            });
            /**
             * отмена строительства
             */
            $(document).on('click', '[data-entity=reject_building_button]', function (evt) {
                return _this2.reject_building(evt);
            });
            /*
            $(document).on('mousedown', '[data-popup_id=popup_building]', (evt) => {
                let obj = $(evt.target).closest('.keep__item-preview');
                let tab = $(obj).data('tab_open');
                $('.popup_building').find('[data-building_tab]').removeClass('is-active');
                $('.popup_building').find('[data-building_tab=building_resources]').addClass('is-active');
            });
            $(document).on('mousedown', '[data-tab-group=building]', (evt) => {
                let obj = $(evt.target);
                let link = $(obj).data('tab-link');
                $('.popup_building').find('[data-building_tab]').removeClass('is-active');
                $('.popup_building').find('[data-building_tab='+link+']').addClass('is-active');
            });
            $(document).on('click', '[data-building_tab]', (evt) => {
                evt.preventDefault();
                let obj = $(evt.target).closest('.popup__head-item');
                let tab = $(obj).data('building_tab');
                $('[data-building_tab]').removeClass('is-active');
                $('[data-building_tab='+tab+']').addClass('is-active');
                $('[data-tab-link='+tab+']').trigger('click');
            });
            */
        }
    }, {
        key: 'reject_building',
        value: function reject_building(evt) {
            var _this3 = this;

            var obj = $(evt.target);
            var root = $(obj).closest('[data-root=root]');
            if (this.is_building_going_on.check(root)) {
                var event_id = parseInt($(root).attr('data-event_id'));
                this.ajaxer.execute('post', '/eventer/rejectbuilding', 'event_id=' + event_id, 'json', function (data) {
                    _this3.restore_context(root);
                });
            } else {
                $.notify('building isnt building');
            }
        }
    }, {
        key: 'restore_context',
        value: function restore_context(root) {
            var event_id = parseInt($(root).attr('data-event_id'));
            var building_id = parseInt($(root).attr('data-building_id'));
            /**
             * 1. гасим таймер
             */
            clearInterval(this.building_timer[event_id]);
            /**
             * 2. обнуляем таймер
             */
            $('[data-event_id=' + event_id + ']').find('[data-entity=timer]').html('00:00:00');
            $('[data-event_id=' + event_id + ']').find('[data-entity_progress]').css('height', '0%');
            /**
             * 3. возвращаем возможность открывать всплывающее окно с выбором зданий
             *    здесь через data() так как через attr не работает
             */
            var data_popup = $(root).find('.keep__item-preview').data('popup').substr(1);
            $(root).find('.keep__item-preview').data('popup', data_popup);
            /**
             * 4. запрещаем открывать окно с отменой/ускорением строительства
             */
            $(root).find('.keep__item-info').css('display', 'none');
            /**
             * 5. убираем евент в корне
             */
            $(root).attr('data-event_id', 0);
            /**
             * 6. возвращаем предыдущую картинку в круг
             */
            //$(root).find('.keep__item-preview-frame, .keep__item-preview-ico').each((idx, elm) => {
            //    if(!$(elm).hasClass('native')) $(elm).remove(); else $(elm).css('display', 'unset');
            //});
            var color = this.colors[$(root).data('entity')];
            $(root).attr('class', color);
            /**
             * 7. скрываем превью, таймер и показываем молоток
             */
            var parent = $(root).parent();
            var process = $(parent).find('[data-process]');
            var preview = $(parent).find('[data-process_window]');
            var timer = $(parent).find('.keep__item-timer');
            $(process).show();
            $(preview).hide();
            $(timer).hide();

            /**
             * 8. убираем таймер со здания
             */
            var element_timer = $('[data-item_identifier=' + building_id + ']').find('.keep__item-timer');
            if ($(element_timer).length) {
                $(element_timer).remove();
            }
        }
    }, {
        key: 'prepare_context',
        value: function prepare_context(root, picture, color, building_id, event_id) {
            $.notify('prepend_context');
            /**
             * 1. убираем возможность открывать всплывающее окно с выбором зданий
             *    здесь через data() так как через attr не работает
             */
            var data_popup = '_' + $(root).find('.keep__item-preview').data('popup');
            $(root).find('.keep__item-preview').data('popup', data_popup);
            /**
             * 2. разрешаем открывать окно с отменой/ускорением строительства
             */
            $(root).find('.keep__item-info').css('display', '');
            /**
             * 3. меняем картинку
             */
            $(root).find('.keep__item-preview-progress').after(picture);
            $(root).attr('class', color);
            /**
             * 4. убираем молоток и включаем превью и включаем таймер
             */
            var parent = $(root).parent();
            var process = $(parent).find('[data-process]');
            var preview = $(parent).find('[data-process_window]');
            var timer = $(parent).find('.keep__item-timer');
            $(process).hide();
            $(preview).show();
            $(timer).show();
            /**
             * 5. в рут ставим id здания
             */
            $(root).attr('data-building_id', building_id);
            /**
             * 6. в рут ставим id события
             */
            $(root).attr('data-event_id', event_id);
        }
    }, {
        key: 'install_reject_button_handler',
        value: function install_reject_button_handler(event_id) {
            var _this4 = this;

            if (!(event_id in this.reject_building_button_handlers)) {
                $(document).on('click', '[data-entity=reject_building_button_' + event_id + ']', function (evt) {
                    _this4.ajaxer.execute('post', '/eventer/rejectbuilding', 'event_id=' + event_id, 'json', function (data) {
                        $.notify(data.message);
                    });
                });
                this.reject_building_button_handlers[event_id] = 1;
            }
        }
    }, {
        key: 'updatePlanetkeep',
        value: function updatePlanetkeep() {
            var _this5 = this;

            this.ajaxer.execute('post', '/app/planetkeep', 'planetid=' + this.current_planet, 'json', function (data) {
                $('[data-entity=game__planet-keep-keep]').html(data.content);
                $('.popup_building').remove();
                $(data.popup_building).insertAfter('.game');
                _this5.start_continue_timer(data.event_id, data.begin, data.end, data.now);
                _this5.install_reject_button_handler(data.event_id);
                window.theGame.plugs.update();
            });
        }
    }, {
        key: 'build',
        value: function build(evt) {
            var _this6 = this;

            /**
             * @ запуск строительства
             */
            var obj = $(evt.target);
            window.theGame.popup.close('.popup');
            $(obj).closest('.popup__pp').removeClass('is-open');
            var building_id = $(obj).closest('.popup__pp').data('building_id');
            var root = $(obj).closest('.popup__pp').data('root');
            var picture = $(obj).closest('.popup__pp').data('picture');
            var color = $(obj).closest('.popup__pp').data('color');
            root = $('[data-entity=' + root + ']').first();
            this.ajaxer.execute('post', '/eventer/initbuild', 'planet=' + this.current_planet + '&buildingType=' + building_id, 'json', function (data) {
                /**
                 * 1. готовим рута
                 */
                $(root).attr('data-event_id', data.event_id);
                /**
                 * 2. готовим контекст
                 */
                _this6.prepare_context(root, picture, color, building_id, data.event_id);
                /**
                 * 3. запускаем строительство
                 */
                _this6.start_continue_timer(data.event_id, data.begin, data.end, data.now, building_id, root);
            });
        }
    }, {
        key: 'buildind_block_checking',
        value: function buildind_block_checking(selector) {
            if (this.is_building_going_on.check(selector)) {
                var root = $(selector);
                var event_id = parseInt($(root).attr('data-event_id'));
                var begin = parseInt($(root).attr('data-event_begin'));
                var end = parseInt($(root).attr('data-event_end'));
                var now = parseInt($(root).attr('data-event_now'));
                var building_id = parseInt($(root).attr('data-building_id'));
                /**
                 * подготовим площадку, сменим картинку
                 */
                var building = $('[data-item_identifier=' + building_id + ']').first();
                var picture = $(building).find('.keep__item-preview').first().html();
                var color = $(building).find('.keep__item').first().attr('class');
                this.colors[$(root).data('entity')] = $(root).attr('class');
                /*
                $(root).find('.keep__item-preview-frame').css('display', 'none').addClass('native');
                $(root).find('.keep__item-preview-ico').css('display', 'none').addClass('native');
                */
                $(root).find('.keep__item-preview-progress').after(picture);
                $(root).attr('class', color);

                this.start_continue_timer(event_id, begin, end, now, building_id, $(selector));
            }
        }
    }, {
        key: 'building_select',
        value: function building_select(evt) {
            var obj = $(evt.target).closest('.build__item');
            var root = $(obj).data('root');
            var id = $(obj).attr('data-item_identifier');
            var period = $(obj).attr('data-building_period');
            var level = $(obj).attr('data-building_level');
            var picture = $(obj).find('.keep__item-preview').first().html();
            var color = $(obj).find('.keep__item').first().attr('class');
            var title = $(obj).find('[data-entity=keep__item-text]').first().html();
            var description = $(obj).find('[data-entity=building-description]').first().html();
            this.colors[root] = $('[data-entity=' + root + ']').first().attr('class');
            $('[data-entity=popup_building-popup__pp]').data('building_id', id);
            $('[data-entity=popup_building-popup__pp]').data('root', root);
            $('[data-entity=popup_building-popup__pp]').data('picture', picture);
            $('[data-entity=popup_building-popup__pp]').data('color', color);
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-content-title]').html(title + '<br><span class="btn btn_lvl">lv.' + level + '</span>');
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-content-description]').html(description);
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-building-period]').html(period);
        }
    }]);

    return PopupBuilding;
}();