'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var JQueryStarter = function () {
    function JQueryStarter() {
        _classCallCheck(this, JQueryStarter);
    }

    _createClass(JQueryStarter, [{
        key: 'run',
        value: function run(callback) {
            var _this = this;

            this.ali = setInterval(function () {
                if (typeof jQuery !== 'function') return;
                clearInterval(_this.ali);
                callback();
            }, 50);
        }
    }]);

    return JQueryStarter;
}();

var Main = function () {
    function Main() {
        var _this2 = this;

        _classCallCheck(this, Main);

        this.runner = new JQueryStarter();
        this.runner.run(function () {
            try {
                console.log('flight loaded');
                _this2.processing();
            } catch (err) {
                console.log(err.message);
            }
        });
    }

    _createClass(Main, [{
        key: 'processing',
        value: function processing() {
            $(document).on('change', 'select', function (evt) {
                evt.preventDefault();
                var name = $(this).attr('name');
                var id = $(this).val();
                switch (name) {
                    case 'galaxy_select':
                    case 'planet_system_select':
                    case 'planet_select':
                    case 'star_select':
                    case 'sputnik_select':
                        break;
                    default:
                        throw name + ' value unexpected!';
                        break;
                }
                $.ajax({
                    type: 'post',
                    url: '/flight/update_selectors',
                    data: 'name=' + name + '&value=' + id,
                    dataType: "json",
                    success: function success(response) {
                        for (var property in response.result) {
                            if (response.result.hasOwnProperty(property)) {
                                var html = '';
                                for (var i = 0; i < response.result[property].length; i++) {
                                    var obj = response.result[property][i];
                                    html += '<option value="' + obj.id + '">' + obj.name + '</option>';
                                }
                                $('select[name=' + property + ']').html(html);
                                $('select[name=' + property + ']').selectpicker('refresh');
                            }
                        }
                    },
                    error: function error(data) {
                        console.error(data);
                    }
                });
                console.log(name + ' - ' + id);
            });
            $(document).on('click', '#flight-calc', function (evt) {
                var target = $('input[name=target]:checked').val();
                var galaxy = $('select[name=galaxy_select]').val();
                var planet_system = $('select[name=planet_system_select]').val();
                var planet = $('select[name=planet_select]').val();
                var star = $('select[name=star_select]').val();
                var sputnik = $('select[name=sputnik_select]').val();
                var error = [];
                if (target == 'planet' && planet == null) error.push('planet did not select');
                if (target == 'sputnik' && sputnik == null) error.push('sputnik did not select');
                if (error.length) {
                    var errtext = '<p>' + error.join('</p><p>') + '</p>';
                    var html = $('#console').html();
                    $('#console').html(html + errtext);
                } else {
                    var html = $('#console').html();
                    $.ajax({
                        type: 'post',
                        url: '/flight/calc',
                        data: 'target=' + target + '&galaxy=' + galaxy + '&planet_system=' + planet_system + '&planet=' + planet + '&star=' + star + '&sputnik=' + sputnik,
                        dataType: "json",
                        success: function success(response) {
                            console.log(response);
                            var html = $('#console').html();
                            $('#console').html(html + response.result);
                        },
                        error: function error(data) {
                            console.error(data);
                        }
                    });
                }
            });
            /*
            this.timer = setInterval(() => {
                $.ajax({
            type: 'post',
            url: '/logger',
            data: '',
            dataType: "json",
            success: function (response) {
            console.log(response.messages);
                        $('#console').html (response.messages);
            },
            error: function(data) {
            console.error(data);
            }
                });
            }, 1500);
            $(document).on ('click', '#universe-start-generation', function(evt) {
                evt.preventDefault();
                $.ajax({
            type: 'post',
            url: '/creator',
            data: '',
            dataType: "json",
            success: function (response) {
            console.log(response.messages);
            },
            error: function(data) {
            console.error(data);
            }
                });
            });
            */
        }
    }]);

    return Main;
}();

var main = new Main();