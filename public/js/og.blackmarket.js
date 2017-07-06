'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.Blackmarket = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _ogAjaxer = require('og.ajaxer.js');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Blackmarket = exports.Blackmarket = function () {
    function Blackmarket(socket) {
        var _this = this;

        _classCallCheck(this, Blackmarket);

        this.socket = socket;
        this.ajaxer = new _ogAjaxer.Ajaxer();
        this.current_planet = $('[data-current_planet]').data('current_planet');

        $(document).on('click', '[data-entity=buy-donate]', function (evt) {
            evt.preventDefault();
            window.theGame.popup.close('.popup');
            window.theGame.popup.open('.popup_buy');
        }).on('click', '[data-entity=money-minus-sign], [data-entity=money-plus-sign]', function (evt) {
            return _this.changemoney(evt);
        }).on('click', '[data-entity=donate-minus-sign], [data-entity=donate-plus-sign]', function (evt) {
            return _this.changedonate(evt);
        }).on('click', '[data-entity=buy-donate]', function (evt) {
            return _this.buydonate(evt);
        }).on('click', '[data-entity=buy-donate-end]', function (evt) {
            return _this.buy_donate_end(evt);
        }).on('click', '[data-entity=blackmarket-source] > .storage__card-inner > .storage__card-buy_violet', function (evt) {
            return _this.buy_source(evt);
        }).on('click', '[data-entity=valute-button]', function (evt) {
            evt.preventDefault();
            $('[data-entity=valute-button]').removeClass('is-active');
            $(evt.target).closest('[data-entity=valute-button]').addClass('is-active');
            var sign = $(evt.target).closest('[data-entity=valute-button]').data('sign');
            _this.set_currency(sign);
        });
        this.socket.on('get_planet_data', function (data) {
            return _this.get_planet_data(data);
        });
    }

    _createClass(Blackmarket, [{
        key: 'buy_source',
        value: function buy_source(evt) {
            evt.preventDefault();
            var parent = $(evt.target).closest('[data-entity=blackmarket-source]');
            if ($(parent).hasClass('is-disable')) {
                $.notify('покупка невозможна', 'error');
            } else {

                //$.notify('покупка возможна', 'info');
                this.ajaxer.execute('post', '/eventer/buysources', 'planet=' + this.current_planet + '&source_fullness_percents_up_to=' + $(parent).data('fullness') + '&source_id=' + $(parent).data('sourcetype'), 'json', function (data) {
                    $.notify(data.message, 'info');
                    if (typeof data.error != 'undefined') $.notify(data.error);
                });
            }
        }
    }, {
        key: 'get_planet_data',
        value: function get_planet_data(data) {
            var result = JSON.parse(data.planet_data);
            var values = result[0];
            $.each($('[data-entity=blackmarket-source]'), function (idx, elt) {
                var source = $(elt).data('sourcetype');
                var price = parseFloat($(elt).data('price'));
                var amount = parseFloat(values['mineral_' + source]);
                var capacity = parseFloat(values[source + '_capacity']);
                var fullness = parseFloat($(elt).data('fullness'));
                var fullness_calc = 100 * parseFloat(amount) / parseFloat(capacity);
                var total = 0;
                if (fullness - 1 >= fullness_calc) {
                    var amountend = Math.round(capacity * (fullness - fullness_calc) / 100);
                    total = Math.round(price * amountend);
                }
                $(elt).find('.val').text(total);
                $(elt).removeClass('is-disable');
                if (total == 0) {
                    $(elt).addClass('is-disable');
                }
                //console.log(source + ' = ' + total);
            });
        }
    }, {
        key: 'buydonate',
        value: function buydonate(evt) {
            evt.preventDefault();
            var target = $(evt.target);
            var amountelt = $(target).closest('.storage__card').find('[data-entity=valute-amount]');
            var donateelt = $(target).closest('.storage__card');
            var valute = parseInt($(amountelt).text().replace(/\s/g, '').replace(/[$,€]/g, ''));
            var donate = $(donateelt).data('donate_amount');
            var sign = this.get_current_sign();
            $('[data-entity=total_4_buy]').text(this.format(Math.round(valute), 0, 3, ' ') + ' ' + sign);
            $('[data-entity=total_4_buy]').data('total_4_buy_donate', donate);
        }
    }, {
        key: 'get_current_sign',
        value: function get_current_sign() {
            return $('[data-currency_sign]').data('currency_sign');
        }
    }, {
        key: 'changedonate',
        value: function changedonate(evt) {
            evt.preventDefault();
            var target = $(evt.target);
            var doing = $(target).data('entity');
            var amountelt = $(target).closest('.storage__card').find('[data-entity=valute-amount]');
            var donateelt = $(target).closest('.storage__card').find('[data-entity=donate-amount]');
            var donatetotalelt = $(target).closest('.storage__card');
            var amount = parseInt($(amountelt).text().replace(/\s/g, '').replace(/[$,€]/g, ''));
            var donate = parseInt($(donateelt).text().replace(/\s/g, '').replace(/[$,€]/g, ''));
            var sign = this.get_current_sign();
            var course = this.getcourse_by_sign(sign);
            switch (doing) {
                case 'donate-minus-sign':
                    donate -= 1;
                    break;
                case 'donate-plus-sign':
                    donate += 1;
                    break;
            }
            amount = Math.round(parseFloat(donate) / parseFloat(course));
            $(amountelt).text(this.format(amount, 0, 3, ' ') + ' ' + sign);
            $(donateelt).text(this.format(donate, 0, 3, ' '));
            $(donatetotalelt).data('donate_amount', donate);
        }
    }, {
        key: 'changemoney',
        value: function changemoney(evt) {
            evt.preventDefault();
            var target = $(evt.target);
            var doing = $(target).data('entity');
            var amountelt = $(target).closest('.storage__card').find('[data-entity=valute-amount]');
            var donateelt = $(target).closest('.storage__card').find('[data-entity=donate-amount]');
            var donatetotalelt = $(target).closest('.storage__card');
            var amount = parseInt($(amountelt).text().replace(/\s/g, '').replace(/[$,€]/g, ''));
            var donate = parseInt($(donateelt).text().replace(/\s/g, '').replace(/[$,€]/g, ''));
            var sign = this.get_current_sign();
            var course = this.getcourse_by_sign(sign);
            switch (doing) {
                case 'money-minus-sign':
                    amount -= 1;
                    break;
                case 'money-plus-sign':
                    amount += 1;
                    break;
            }
            donate = Math.round(parseFloat(amount) * parseFloat(course));
            $(amountelt).text(this.format(amount, 0, 3, ' ') + ' ' + sign);
            $(donateelt).text(this.format(donate, 0, 3, ' '));
            $(donatetotalelt).data('donate_amount', donate);
        }
    }, {
        key: 'set_currency',
        value: function set_currency(sign) {
            $('[data-currency_sign]').data('currency_sign', sign);
            var course = this.getcourse_by_sign(sign);
            this.change2valute(sign, course);
        }
    }, {
        key: 'change2valute',
        value: function change2valute(sign, course) {
            var _this2 = this;

            $.each($('[data-donate_amount]'), function (idx, elt) {
                var donate = parseInt($(elt).data('donate_amount'));
                var valute = donate / course;
                $(elt).find('[data-entity=valute-amount]').html(_this2.format(Math.round(valute), 0, 3, ' ') + ' ' + sign);
            });
        }
    }, {
        key: 'getcourse_by_sign',
        value: function getcourse_by_sign(sign) {
            var course = 0;
            switch (sign) {
                case '$':
                    course = parseFloat($('[data-donate_price_usd]').data('donate_price_usd'));
                    break;
                case '€':
                    course = parseFloat($('[data-donate_price_usd]').data('donate_price_eur'));
                    break;
            }
            return course;
        }
    }, {
        key: 'buy_donate_end',
        value: function buy_donate_end(evt) {
            evt.preventDefault();
            var amount = $('[data-entity=total_4_buy]').data('total_4_buy_donate');
            this.ajaxer.execute('post', '/eventer/buydonate', 'planet=' + this.current_planet + '&amount=' + amount, 'json', function (data) {
                $.notify(data.message);
                window.theGame.popup.close('.popup');
            });
        }
    }, {
        key: 'format',
        value: function format(number, n, x, s, c) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
                num = number.toFixed(Math.max(0, ~~n));

            return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
        }
    }]);

    return Blackmarket;
}();