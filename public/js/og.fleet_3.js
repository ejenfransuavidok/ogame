'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.Fleet_3 = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogValidators = require('og.validators.js');

var _ogGlobal_vars = require('og.global_vars.js');

var _ogAjaxer = require('og.ajaxer.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Fleet_3 = exports.Fleet_3 = function () {
    function Fleet_3() {
        _classCallCheck(this, Fleet_3);

        this.validator = new _ogValidators.Validator();
        this.globalvars = new _ogGlobal_vars.GlobalVars();
        this.ajaxer = new _ogAjaxer.Ajaxer();
        if ($('.popup_fleet_3').length) {
            this.exec();
        }
    }

    _createClass(Fleet_3, [{
        key: 'exec',
        value: function exec() {
            var _this = this;

            $(document).on('click', '[data-entity=popup_fleet_3_go]', function (evt) {
                return _this.fleet_go(evt);
            });
        }
    }, {
        key: 'fleet_go',
        value: function fleet_go(evt) {
            var _this2 = this;

            var current_planet = this.globalvars.getCurrentPlanet();
            var target_galaxy = $('[data-target_entity=galaxy]').val();
            var target_planet_system = $('[data-target_entity=planet_system]').val();
            var target_planet = $('[data-target_entity=planet]').val();
            if (this.validator.validateInteger(target_galaxy) && this.validator.validateInteger(target_planet_system) && this.validator.validateInteger(target_planet)) {
                this.ajaxer.execute('post', '/app/setuptarget', 'current_planet=' + current_planet + '&target_galaxy=' + target_galaxy + '&target_planet_system=' + target_planet_system + '&target_planet=' + target_planet, 'json', function (data) {
                    console.log(data.fstc_CanGetTarget);
                    if (data.fstc_CanGetTarget == false) {
                        $.notify('Топлива недостаточно!');
                        /**
                         * @ недолет в один конец, в оба даже не проверяем
                         */
                    } else {
                        $.notify('Долетим!');
                        /**
                         * @ полетим
                         * current_planet
                         * target_galaxy
                         * target_planet_system
                         * target_planet
                         */
                        _this2.ajaxer.execute('post', '/app/fleetlaunch', 'current_planet=' + current_planet + '&target_galaxy=' + target_galaxy + '&target_planet_system=' + target_planet_system + '&target_planet=' + target_planet, 'json', function (data) {
                            $.notify(data.message);
                            window.theGame.popup.close('.popup');
                            _this2.ajaxer.execute('post', '/app/fleetforwardpopupupdater', 'current_planet=' + current_planet, 'json', function (data) {
                                var fleet_forward_1 = data.fleet_forward_1;
                                var fleet_forward_2 = data.fleet_forward_2;
                                var fleet_forward_3 = data.fleet_forward_3;
                                var fleet_mooving_activity = data.fleet_mooving_activity;

                                fleet_forward_1 = fleet_forward_1.split('<!-- join -->');
                                fleet_forward_1[0] = fleet_forward_1[2] = '';
                                fleet_forward_1 = fleet_forward_1.join();
                                fleet_forward_2 = fleet_forward_2.split('<!-- join -->');
                                fleet_forward_2[0] = fleet_forward_2[2] = '';
                                fleet_forward_2 = fleet_forward_2.join();
                                fleet_forward_3 = fleet_forward_3.split('<!-- join -->');
                                fleet_forward_3[0] = fleet_forward_3[2] = '';
                                fleet_forward_3 = fleet_forward_3.join();

                                $('.popup_fleet_1').html(fleet_forward_1);
                                $('.popup_fleet_2').html(fleet_forward_2);
                                $('.popup_fleet_3').html(fleet_forward_3);
                                $('.game__force').html(fleet_mooving_activity);

                                window.theGame.plugs.update();
                            });
                        });
                    }
                });
            } else {
                $.notify('ошибка ввода');
            }
        }
    }]);

    return Fleet_3;
}();