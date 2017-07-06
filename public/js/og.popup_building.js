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
        this.timers_counters = [];
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
        key: 'update_building_into_list',
        value: function update_building_into_list(building_id) {
            var current_level = parseInt($('[data-item_identifier=' + building_id + ']').attr('data-building_level'));
            var price_factor = parseFloat($('[data-item_identifier=' + building_id + ']').attr('data-building_price_factor'));
            var new_period = parseInt(Math.pow(parseFloat($('[data-item_identifier=' + building_id + ']').attr('data-building_price_factor')), current_level) * parseInt($('[data-item_identifier=' + building_id + ']').attr('data-summ_resource_consume')) / 30);
            $('[data-item_identifier=' + building_id + ']').attr('data-building_period', this.interval.interval2string(new_period));
            $('[data-item_identifier=' + building_id + ']').attr('data-building_level', parseInt(current_level + 1));
            $('[data-item_identifier=' + building_id + ']').find('[data-entity=keep__item-lvl]').html('(' + parseInt(current_level + 1) + ')');
        }
    }, {
        key: 'start_continue_timer',
        value: function start_continue_timer(event_id, begin, end, now, building_id, main) {
            var _this = this;

            var evt_id = parseInt(event_id);
            var evt_begin = parseInt(begin);
            var evt_end = parseInt(end);
            var evt_now = parseInt(now);
            this.timers_counters[event_id] = evt_end - evt_now;
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
                if (_this.timers_counters[event_id] <= -1) {
                    clearInterval(_this.building_timer[event_id]);
                    var current_level = parseInt($('[data-item_identifier=' + building_id + ']').attr('data-building_level'));
                    var price_factor = parseFloat($('[data-item_identifier=' + building_id + ']').attr('data-building_price_factor'));
                    var new_period = parseInt(Math.pow(parseFloat($('[data-item_identifier=' + building_id + ']').attr('data-building_price_factor')), current_level) * parseInt($('[data-item_identifier=' + building_id + ']').attr('data-summ_resource_consume')) / 30);
                    $('[data-item_identifier=' + building_id + ']').attr('data-building_period', _this.interval.interval2string(new_period));
                    $('[data-item_identifier=' + building_id + ']').attr('data-building_level', parseInt(current_level + 1));
                    $('[data-item_identifier=' + building_id + ']').find('[data-entity=keep__item-lvl]').html('(' + parseInt(current_level + 1) + ')');
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
                    var time = _this.interval.resttime2string(_this.timers_counters[event_id], evt_begin, evt_end);
                    var progress = Math.ceil(100 - 100 * _this.timers_counters[event_id] / interval);
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
                    _this.timers_counters[event_id]--;
                }
            }, 1000);
        }
    }, {
        key: 'exec',
        value: function exec() {
            var _this2 = this;

            this.current_planet = $('[data-current_planet]').data('current_planet');
            $(document).on('click', '[data-entity=Resources], [data-entity=Plants]', function (evt) {
                return _this2.building_select(evt);
            });
            /**
             * здесь строительство только для ресурсного и индустриального здания !!! (остальные пока не предусмотрены!!!)
             */
            $(document).on('click', '[data-entity=popup_ship_build_button]', function (evt) {
                return _this2.build(evt);
            });
            /**
             * отмена строительства
             */
            $(document).on('click', '[data-entity=reject_building_button]', function (evt) {
                return _this2.reject_building(evt);
            });
            /**
             * достроить за донат
             */
            $(document).on('click', '[data-entity=finish_4_donate_building_button]', function (evt) {
                return _this2.finish_4_donate(evt);
            });
            /**
             * построить за донат
             */
            $(document).on('click', '[data-entity=building_4_donate_building_button]', function (evt) {
                return _this2.building_4_donate(evt);
            });
            /**
             * открыть всплывающее окно строительства
             */
            $(document).on('click', '[data-entity=hammer]', function (evt) {
                return _this2.open_popup_building(evt);
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
        key: 'building_4_donate',
        value: function building_4_donate(evt) {
            var _this3 = this;

            evt.preventDefault();
            var win = $('.popup_ship');
            var building_id = $(win).data('building_id');
            this.ajaxer.execute('post', '/eventer/buildingfordonate', 'planet=' + this.current_planet + '&buildingtypeid=' + building_id, 'json', function (data) {
                $.notify(data.message);
                window.theGame.popup.close('.popup');
                _this3.update_building_into_list(building_id);
            });
        }
    }, {
        key: 'finish_4_donate',
        value: function finish_4_donate(evt) {
            var _this4 = this;

            evt.preventDefault();
            var obj = $(evt.target);
            var root = $(obj).closest('[data-root=root]');
            if (this.is_building_going_on.check(root)) {
                (function () {
                    var event_id = parseInt($(root).attr('data-event_id'));
                    _this4.ajaxer.execute('post', '/eventer/finishfordonatebuilding', 'event_id=' + event_id, 'json', function (data) {
                        /**
                         * @ закончим строительство
                         */
                        _this4.timers_counters[event_id] = -1;
                    });
                })();
            } else {
                $.notify('building isnt building');
            }
        }
    }, {
        key: 'reject_building',
        value: function reject_building(evt) {
            var _this5 = this;

            var obj = $(evt.target);
            var root = $(obj).closest('[data-root=root]');
            if (this.is_building_going_on.check(root)) {
                var event_id = parseInt($(root).attr('data-event_id'));
                this.ajaxer.execute('post', '/eventer/rejectbuilding', 'planet=' + this.current_planet, 'json', function (data) {
                    _this5.restore_context(root);
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
             * 3. скрываем процесс и открываем молоток
             */
            var parent = $(root).parent();
            var process = $(parent).find('[data-entity=building-column]');
            var hammer = $(parent).find('[data-entity=hammer]');
            $(process).hide();
            $(hammer).show();
            /**
             * 4. убираем евент в корне
             */
            $(root).attr('data-event_id', 0);
            /**
             * 5. убираем таймер со здания
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
             * 1. меняем картинку
             */
            $(root).find('[data-entity=building-ico]').html(picture);
            $(root).css('color', color);
            /**
             * 2. убираем молоток и включаем превью и включаем таймер
             */
            var parent = $(root).parent();
            var process = $(parent).find('[data-entity=building-column]');
            var hammer = $(parent).find('[data-entity=hammer]');
            $(process).show();
            $(hammer).hide();
            /**
             * 3. в рут ставим id здания
             */
            $(root).attr('data-building_id', building_id);
            /**
             * 4. в рут ставим id события
             */
            $(root).attr('data-event_id', event_id);
        }
    }, {
        key: 'install_reject_button_handler',
        value: function install_reject_button_handler(event_id) {
            var _this6 = this;

            if (!(event_id in this.reject_building_button_handlers)) {
                $(document).on('click', '[data-entity=reject_building_button_' + event_id + ']', function (evt) {
                    _this6.ajaxer.execute('post', '/eventer/rejectbuilding', 'event_id=' + event_id, 'json', function (data) {
                        $.notify(data.message);
                    });
                });
                this.reject_building_button_handlers[event_id] = 1;
            }
        }
    }, {
        key: 'updatePlanetkeep',
        value: function updatePlanetkeep() {
            var _this7 = this;

            this.ajaxer.execute('post', '/app/planetkeep', 'planetid=' + this.current_planet, 'json', function (data) {
                $('[data-entity=game__planet-keep-keep]').html(data.content);
                $('.popup_building').remove();
                $(data.popup_building).insertAfter('.game');
                _this7.start_continue_timer(data.event_id, data.begin, data.end, data.now);
                _this7.install_reject_button_handler(data.event_id);
                window.theGame.plugs.update();
            });
        }
    }, {
        key: 'build',
        value: function build(evt) {
            var _this8 = this;

            /**
             * @ запуск строительства
             */
            window.theGame.popup.close('.popup');
            var obj = $(evt.target);
            var win = $(obj).closest('.popup_ship');
            $(win).removeClass('is-open');
            var building_id = $(win).data('building_id');
            var root = $(win).data('root');
            var picture = $(win).data('picture');
            var color = $(win).data('color');
            root = $('[data-entity=' + root + ']').first();
            this.ajaxer.execute('post', '/eventer/initbuild', 'planet=' + this.current_planet + '&buildingType=' + building_id, 'json', function (data) {
                /**
                 * 1. готовим рута
                 */
                $(root).attr('data-event_id', data.event_id);
                /**
                 * 2. готовим контекст
                 */
                _this8.prepare_context(root, picture, color, building_id, data.event_id);
                /**
                 * 3. запускаем строительство
                 */
                _this8.start_continue_timer(data.event_id, data.begin, data.end, data.now, building_id, root);
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
            var level = parseFloat($(obj).attr('data-building_level'));
            var picture = $(obj).find('.storage__item-ico').first().html();
            var color = $(obj).find('.storage__item_done').first().css('color');
            var title = $(obj).find('[data-entity=keep__item-text]').first().html();
            var description = $(obj).find('[data-entity=building-description]').first().html();
            var price_factor = parseFloat($(obj).data('building_price_factor'));
            var consume_metall = parseFloat($(obj).data('consume_metall'));
            var consume_heavygas = parseFloat($(obj).data('consume_heavygas'));
            var consume_ore = parseFloat($(obj).data('consume_ore'));
            var consume_hydro = parseFloat($(obj).data('consume_hydro'));
            var consume_titan = parseFloat($(obj).data('consume_titan'));
            var consume_darkmatter = parseFloat($(obj).data('consume_darkmatter'));
            var consume_redmatter = parseFloat($(obj).data('consume_redmatter'));
            var consume_anti = parseFloat($(obj).data('consume_anti'));
            var power_factor = parseFloat($(obj).data('power_factor'));
            var donate_needle = 0; //parseFloat($(obj).data('total_donate_needle'));

            var names = {};
            names.metall = ['металл', 'металла'];
            names.heavygas = ['тяжелый газ', 'тяжелого газа'];
            names.ore = ['шахта', 'шахты'];
            names.hydro = ['водород', 'водорода'];
            names.titan = ['титан', 'титана'];
            names.darkmatter = ['темная материя', 'темной материи'];
            names.redmatter = ['красная материя', 'красной материи'];
            names.anti = ['антивещество', 'антивещества'];
            names.electricity = ['электричество', 'электричества'];

            var pictures = {};
            pictures.electricity = $('[data-resources=electricity]').prev().find('img').attr('src');
            pictures.metall = $('[data-resources=metall]').prev().find('img').attr('src');
            pictures.heavygas = $('[data-resources=heavygas]').prev().find('img').attr('src');
            pictures.ore = $('[data-resources=ore]').prev().find('img').attr('src');
            pictures.hydro = $('[data-resources=hydro]').prev().find('img').attr('src');
            pictures.titan = $('[data-resources=titan]').prev().find('img').attr('src');
            pictures.darkmatter = $('[data-resources=darkmatter]').prev().find('img').attr('src');
            pictures.redmatter = $('[data-resources=redmatter]').prev().find('img').attr('src');
            pictures.anti = $('[data-resources=anti]').prev().find('img').attr('src');

            var consume = {};
            var K = Math.pow(parseFloat(price_factor), level - 1); //level == 1 ? 1 : Math.pow(parseFloat(price_factor), level - 1) - Math.pow(parseFloat(price_factor), level - 2);
            consume.metall = parseFloat(consume_metall) * K;
            consume.heavygas = parseFloat(consume_heavygas) * K;
            consume.ore = parseFloat(consume_ore) * K;
            consume.hydro = parseFloat(consume_hydro) * K;
            consume.titan = parseFloat(consume_titan) * K;
            consume.darkmatter = parseFloat(consume_darkmatter) * K;
            consume.redmatter = parseFloat(consume_redmatter) * K;
            consume.anti = parseFloat(consume_anti) * K;
            consume.electricity = parseFloat(power_factor) * K;

            var are_electricity = parseFloat($('[data-resources=electricity]').data('resources_amount'));
            var are_metall = parseFloat($('[data-resources=metall]').data('resources_amount'));
            var are_heavygas = parseFloat($('[data-resources=heavygas]').data('resources_amount'));
            var are_ore = parseFloat($('[data-resources=ore]').data('resources_amount'));
            var are_hydro = parseFloat($('[data-resources=hydro]').data('resources_amount'));
            var are_titan = parseFloat($('[data-resources=titan]').data('resources_amount'));
            var are_darkmatter = parseFloat($('[data-resources=darkmatter]').data('resources_amount'));
            var are_redmatter = parseFloat($('[data-resources=redmatter]').data('resources_amount'));
            var are_anti = parseFloat($('[data-resources=anti]').data('resources_amount'));

            var different = {};
            different.metall = are_metall - consume.metall;
            different.heavygas = are_heavygas - consume.heavygas;
            different.ore = are_ore - consume.ore;
            different.hydro = are_hydro - consume.hydro;
            different.titan = are_titan - consume.titan;
            different.darkmatter = are_darkmatter - consume.darkmatter;
            different.redmatter = are_redmatter - consume.redmatter;
            different.anti = are_anti - consume.anti;
            different.electricity = are_electricity - consume.electricity;

            var donate_prices = {};
            donate_prices.metall = parseFloat($('[data-resources=metall]').data('donate_price'));
            donate_prices.heavygas = parseFloat($('[data-resources=heavygas]').data('donate_price'));
            donate_prices.ore = parseFloat($('[data-resources=ore]').data('donate_price'));
            donate_prices.hydro = parseFloat($('[data-resources=hydro]').data('donate_price'));
            donate_prices.titan = parseFloat($('[data-resources=titan]').data('donate_price'));
            donate_prices.darkmatter = parseFloat($('[data-resources=darkmatter]').data('donate_price'));
            donate_prices.redmatter = parseFloat($('[data-resources=redmatter]').data('donate_price'));

            var needles = '';
            for (var key in consume) {
                if (consume[key] != 0) {
                    var cl = different[key] > 0 ? ' green ' : ' red ';
                    needles += '<div class="resourses__item"><i class="ico"><img style="height: 16px; width: 16px;" src="' + pictures[key] + '">' + '</i><span class="val ' + cl + '">' + Math.round(consume[key]) + '</span></div>';
                    if (key in consume && key in donate_prices) {
                        donate_needle += parseFloat(consume[key]) * donate_prices[key];
                    }
                }
            }

            ///
            var have_resources_for_buildings = true;
            for (var _key in different) {
                if (parseFloat(different[_key]) < 0) have_resources_for_buildings = false;
            }
            var have_donate_for_buildings = are_anti > donate_needle;
            if (!have_resources_for_buildings) {
                if (!$('[data-entity=popup_ship_build_button]').hasClass('is-disable')) {
                    $('[data-entity=popup_ship_build_button]').addClass('is-disable');
                    $('[data-entity=popup_ship_build_button]').text('Недоступно');
                    if (!$('.popup_ship').find('.popup__content-tax-resourses').hasClass('is-over')) {
                        $('.popup_ship').find('.popup__content-tax-resourses').addClass('is-over');
                    }
                }
            } else {
                $('[data-entity=popup_ship_build_button]').removeClass('is-disable');
                $('.popup_ship').find('.popup__content-tax-resourses').removeClass('is-over');
                $('[data-entity=popup_ship_build_button]').text('Строить');
            }
            if (!have_donate_for_buildings) {
                $('.popup_ship').find('.popup__footer-right').hide();
            } else {
                $('.popup_ship').find('.popup__footer-right').show();
            }
            ///

            this.colors[root] = $(obj).find('.storage__item_done').css('color');

            var win = $('.popup_ship');
            $(win).data('building_id', id);
            $(win).data('root', root);
            $(win).data('picture', picture);
            $(win).data('color', color);
            $(win).find('.popup__header-title').html(title);
            $(win).find('.popup__content-text').html('<p>' + description + '</p>');
            $(win).find('.popup__content-info-title').html('Уровень ' + level);
            $(win).find('[data-entity=period]').html(period);
            $(win).find('.resourses__list').html(needles);
            $(win).find('[data-entity=total_donate_needle]').html(Math.round(donate_needle));

            window.theGame.popup.open('.popup_ship');
        }
    }, {
        key: 'open_popup_building',
        value: function open_popup_building(evt) {
            var obj = $(evt.target).closest('[data-entity=hammer]');
            var popup = $(obj).data('tab-popup');
            $('.popup__head-menu-group > [data-tab-group]').removeClass('is-active');
            $('.popup__head-menu-group > [data-tab-link=' + popup + ']').trigger('click');
            window.theGame.popup.close('.popup');
            window.theGame.popup.open('.popup_building');
        }
    }]);

    return PopupBuilding;
}();