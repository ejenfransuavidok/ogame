'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.Fleet_2 = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogValidators = require('og.validators.js');

var _ogGlobal_vars = require('og.global_vars.js');

var _ogAjaxer = require('og.ajaxer.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Fleet_2 = exports.Fleet_2 = function () {
    function Fleet_2() {
        _classCallCheck(this, Fleet_2);

        this.validator = new _ogValidators.Validator();
        this.globalvars = new _ogGlobal_vars.GlobalVars();
        this.ajaxer = new _ogAjaxer.Ajaxer();
        this.can_get_end = false;
        if ($('.popup_fleet_2').length) {
            this.exec();
        }
    }

    _createClass(Fleet_2, [{
        key: 'exec',
        value: function exec() {
            var _this = this;

            $(document).on('keyup', '.fleet__deliver-coordinates-item', function (evt) {
                return _this.fleet__delivery_coordinates_change(evt);
            });
        }
    }, {
        key: 'fleet__delivery_coordinates_change',
        value: function fleet__delivery_coordinates_change(evt) {
            var _this2 = this;

            var obj = $(evt.target);
            if (this.validator.validateInteger($(obj).val())) {
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
                             * @ недолет
                             */
                            _this2.can_get_end = false;
                        } else {
                            $.notify('Долетим!');
                            /**
                             * @ долетим
                             */
                            _this2.can_get_end = true;
                        }
                        $('[data-entity=fstc_Time2OneEnd]').text(data.fstc_Time2OneEnd);
                        $('[data-entity=fstc_SpendFuelAtOneEnd]').text(data.fstc_SpendFuelAtOneEnd);
                        $('[data-entity=fstc_Speed]').text(data.fstc_Speed);
                        $('[data-entity=fstc_Arrival]').text(data.fstc_Arrival);
                        $('[data-entity=fstc_Comeback]').text(data.fstc_Comeback);
                        $('[data-entity=fstc_Capacity]').text(data.fstc_Capacity);
                        $('[data-entity=fstc_LightYears]').html('Расстояние<br>' + data.fstc_LightYears + ' свет лет');
                    });
                } else {
                    $.notify('ошибка ввода');
                }
            } else {
                $.notify('ошибка ввода');
            }
        }
    }]);

    return Fleet_2;
}();