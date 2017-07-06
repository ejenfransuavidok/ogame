'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.SrcUpdater = undefined;

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogGlobal_vars = require('og.global_vars.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SrcUpdater = exports.SrcUpdater = function () {
    function SrcUpdater(socket) {
        var _this = this;

        _classCallCheck(this, SrcUpdater);

        this.socket = socket;
        if ($('[data-resources]').length) {
            this.current_planet = $('[data-current_planet]').data('current_planet');
            this.timer = setInterval(function () {
                _this.resources_ticker();
            }, 1000);
            this.socket.on('get_planet_data', function (data) {
                var result = JSON.parse(data.planet_data);
                var values = result[0];
                try {
                    var metall = $('[data-resources=metall]');
                    $(metall).data('resource_velocity_per_second', values.velocity_per_second_mineral_metall);
                    $(metall).data('resources_limit', values.metall_capacity);
                    $(metall).data('resources_amount', values.mineral_metall);
                    _this.prettier(values.mineral_metall, 'metall');

                    var heavygas = $('[data-resources=heavygas]');
                    $(heavygas).data('resource_velocity_per_second', values.velocity_per_second_mineral_heavygas);
                    $(heavygas).data('resources_limit', values.heavygas_capacity);
                    $(heavygas).data('resources_amount', values.mineral_heavygas);
                    _this.prettier(values.mineral_heavygas, 'heavygas');

                    var ore = $('[data-resources=ore]');
                    $(ore).data('resource_velocity_per_second', values.velocity_per_second_mineral_ore);
                    $(ore).data('resources_limit', values.ore_capacity);
                    $(ore).data('resources_amount', values.mineral_ore);
                    _this.prettier(values.mineral_ore, 'ore');

                    var hydro = $('[data-resources=hydro]');
                    $(hydro).data('resource_velocity_per_second', values.velocity_per_second_mineral_hydro);
                    $(hydro).data('resources_limit', values.hydro_capacity);
                    $(hydro).data('resources_amount', values.mineral_hydro);
                    _this.prettier(values.mineral_hydro, 'hydro');

                    var titan = $('[data-resources=titan]');
                    $(titan).data('resource_velocity_per_second', values.velocity_per_second_mineral_titan);
                    $(titan).data('resources_limit', values.titan_capacity);
                    $(titan).data('resources_amount', values.mineral_titan);
                    _this.prettier(values.mineral_titan, 'titan');

                    var darkmatter = $('[data-resources=darkmatter]');
                    $(darkmatter).data('resource_velocity_per_second', values.velocity_per_second_mineral_darkmatter);
                    $(darkmatter).data('resources_limit', values.darkmatter_capacity);
                    $(darkmatter).data('resources_amount', values.mineral_darkmatter);
                    _this.prettier(values.mineral_darkmatter, 'darkmatter');

                    var redmatter = $('[data-resources=redmatter]');
                    $(redmatter).data('resource_velocity_per_second', values.velocity_per_second_mineral_redmatter);
                    $(redmatter).data('resources_limit', values.redmatter_capacity);
                    $(redmatter).data('resources_amount', values.mineral_redmatter);
                    _this.prettier(values.mineral_redmatter, 'redmatter');

                    var electricity = $('[data-resources=electricity]');
                    $(electricity).data('resource_velocity_per_second', 0);
                    $(electricity).data('resources_limit', 1000000000);
                    $(electricity).data('resources_amount', values.electricity);
                    _this.prettier(values.electricity, 'electricity');

                    var anti = $('[data-resources=anti]');
                    $(anti).data('resource_velocity_per_second', 0);
                    $(anti).data('resources_limit', 1000000000);
                    $(anti).data('resources_amount', values.mineral_anti);
                    _this.prettier(values.mineral_anti, 'anti');
                } catch (err) {
                    console.log(err.message);
                }
            });
        }
    }

    _createClass(SrcUpdater, [{
        key: 'resources_ticker',
        value: function resources_ticker() {
            var _this2 = this;

            $('[data-resources]').each(function (idx, elm) {
                if (_typeof($(elm).attr('data-resource_velocity_per_second')) != undefined) {
                    var old_src = parseFloat($(elm).data('resources_amount'));

                    var velocity = 5 * parseFloat($(elm).data('resource_velocity_per_second'));

                    var new_src = parseFloat(old_src) + parseFloat(velocity);

                    var capacity = parseFloat($(elm).data('resources_capacity'));

                    new_src = new_src <= capacity ? new_src : capacity;

                    $(elm).text(parseInt(Math.floor(new_src)));

                    $(elm).data('resources_amount', new_src);

                    _this2.prettier(new_src, $(elm).data('resources'));
                }
            });
        }
    }, {
        key: 'prettier',
        value: function prettier(value, key) {
            var val = parseInt(value);
            if (val > 1000000) {
                val = Math.ceil(val / 1000000);
                $('[data-resources_min=' + key + ']').text(val + 'm');
            } else if (val > 1000) {
                val = Math.ceil(val / 1000);
                $('[data-resources_min=' + key + ']').text(val + 'k');
            } else {
                $('[data-resources_min=' + key + ']').text(val);
            }
        }

        /*
        updater()
        {
            $.ajax({
                type: 'post',
                url: '/cron/updater/srcupdater',
                data: 'current_planet=' + this.current_planet,
                dataType: "json",
                complete: (res) => {
                    try {
                        let data = res.responseJSON;
                        if(res.status){
                            let status = res.status;
                            if (status == 200){
                                if(data.result == 'YES'){
                                    let values = data.values;
                                    if(Object.keys(values).length){
                                        for(let key in values){
                                            $('[data-resources='+key+']').text(values[key]);
                                            let val = parseInt(values[key]);
                                            if(val > 1000000){
                                                val = Math.ceil(val / 1000000);
                                                $('[data-resources_min='+key+']').text(val+'m');
                                            }
                                            else if(val > 1000){
                                                val = Math.ceil(val / 1000);
                                                $('[data-resources_min='+key+']').text(val+'k');
                                            }
                                            else{
                                                $('[data-resources_min='+key+']').text(val);
                                            }
                                        }
                                    }
                                }
                                else{
                                    $.notify(data.message);
                                }
                            }
                            else{
                                $.notify ( 'Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!' );
                            }
                        }
                        else {
                            $.notify ( 'Во время запроса произошла непредвиденная ошибка, пожалуйста, обратитесь к администратору!' );
                        }
                    }
                    catch (err) {
                        $.notify ( err.message );
                    }
                }
            });
        }
        */

    }]);

    return SrcUpdater;
}();