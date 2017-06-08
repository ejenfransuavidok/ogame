'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Donate = exports.Donate = function () {
    function Donate() {
        var _this = this;

        _classCallCheck(this, Donate);

        var popup = void 0;
        //let this = this;
        this.valuta = '$';

        $(document).on('click', '[data-role=donate-button]', function (evt) {
            _this.theGame = window.theGame;
            if ($(evt.target).prop("tagName") == 'DIV') {
                var field = $(evt.target).find('input').first();
                var donate_amount = parseFloat($(field).val());
                var donate_price = parseFloat($(evt.target).data('donate_price'));
                if (!isNaN(donate_amount)) {
                    _this.theGame.popup.close('.popup_blackmarket');
                    _this.theGame.popup.open('.popup_buy');
                    $('.buy__pay-result').html('К оплате: ' + parseFloat(donate_amount * donate_price) + ' Р');
                    console.log(donate_amount * donate_price);
                }
            }
        }).on('click', '[data-money]', function (evt) {
            _this.theGame = window.theGame;
            _this.valuta = $(evt.target).closest('[data-money]').data('money');
            $('.popup_donut').data('valuta', _this.valuta);
            var current_course_by_valuta = _this.get_current_course_by_valuta();
            $('[data-simbol=valuta]').text(_this.valuta);
            $.each($('[data-donate_amount]'), function (idx, elt) {
                var amount = parseFloat($(elt).data('donate_amount'));
                var price = parseFloat(amount * current_course_by_valuta).toFixed(2);
                $(elt).find('.popup__donut-price').text(price + ' ' + _this.valuta);
            });
            var donate = parseInt($('[data-role=donate-value]').val());
            var cash = parseFloat(donate * current_course_by_valuta);
            _this.setcash(cash);
            _this.theGame.popup.open('.popup_donut');
        }).on('click', '[data-tab_valute]', function (evt) {
            _this.theGame = window.theGame;
            _this.valuta = $(evt.target).data('tab_valute');
            $('.popup_donut').data('valuta', _this.valuta);
            var current_course_by_valuta = _this.get_current_course_by_valuta();
            $('[data-simbol=valuta]').text(_this.valuta);
            $.each($('[data-donate_amount]'), function (idx, elt) {
                var amount = parseFloat($(elt).data('donate_amount'));
                var price = parseFloat(amount * current_course_by_valuta).toFixed(2);
                $(elt).find('.popup__donut-price').text(price + ' ' + _this.valuta);
            });
            var donate = parseInt($('[data-role=donate-value]').val());
            var cash = parseFloat(donate * _this.getcourse());
            _this.setcash(cash);
        });

        $(document).on('click', '[data-role=donate-down], [data-role=donate-up], [data-role=cash-down], [data-role=cash-up]', function (evt) {
            var obj = $(evt.target);
            var course = _this.getcourse();
            var donate = _this.getdonate();
            var cash = _this.getcash();
            switch ($(obj).data('role')) {
                case 'donate-down':
                    _this.changedonate(-1);
                    break;
                case 'donate-up':
                    _this.changedonate(1);
                    break;
                case 'cash-down':
                    _this.changecash(-1);
                    break;
                case 'cash-up':
                    _this.changecash(1);
                    break;
                default:
                    $.notify('undefined');
                    break;
            }
        }).on('keyup', '[data-role=donate-value], [data-role=cash-value]', function (evt) {
            var target = $(evt.target);
            if ($(target).data('role') == "donate-value") {
                var donate = _this.getdonate();
                var course = _this.getcourse();
                var cash = parseFloat(course) * parseFloat(donate);
                _this.setcash(cash);
            } else {
                var _cash = parseFloat(_this.getcash());
                var _course = _this.getcourse();
                var _donate = parseFloat(_cash / _course);
                _this.setdonate(_donate);
                _this.setcash(_cash);
            }
        }).on('click', '[data-role=donate]', function (evt) {
            _this.theGame = window.theGame;
            var target = $(evt.target);
            var amount = $(target).closest('.popup__donut-item');
            amount = parseInt($(amount).data('donate_amount'));
            var current_course_by_valuta = _this.get_current_course_by_valuta();
            var cash = amount * current_course_by_valuta;
            $('.buy__pay-result').text('К оплате: ' + cash.toFixed(2) + ' ' + _this.valuta + '.');
            _this.theGame.popup.close('.popup');
            _this.theGame.popup.open('.popup_buy');
        });

        if (popup = this.getUrlParameter('popup_window')) {
            this.theGame = window.theGame;
            this.theGame.popup.open(popup);
        }
    }

    _createClass(Donate, [{
        key: 'changecash',
        value: function changecash(sign) {
            var delta = parseFloat(sign) * parseFloat(this.getcourse());
            var cash = this.getcash();
            cash += delta;
            if (cash < 0) return false;
            this.setcash(cash);
            var donate = this.getdonate();
            donate += sign;
            this.setdonate(donate);
        }
    }, {
        key: 'changedonate',
        value: function changedonate(sign) {
            var delta = parseFloat(sign);
            var donate = this.getdonate();
            donate += sign;
            if (donate < 0) return false;
            this.setdonate(donate);
            var cash = 0;
            var course = this.getcourse();
            cash = parseFloat(course) * parseFloat(donate);
            this.setcash(cash);
        }
    }, {
        key: 'getcourse',
        value: function getcourse() {
            return parseFloat($('[data-donate]').data('donate')) * this.get_current_course_by_valuta();
        }
    }, {
        key: 'getdonate',
        value: function getdonate() {
            return parseFloat($('[data-role=donate-value]').val());
        }
    }, {
        key: 'getcash',
        value: function getcash() {
            return parseFloat($('[data-role=cash-value]').val());
        }
    }, {
        key: 'setdonate',
        value: function setdonate(donate) {
            $('[data-role=donate-value]').val(donate.toFixed(2));
        }
    }, {
        key: 'setcash',
        value: function setcash(cash) {
            $('[data-role=cash-value]').val(cash.toFixed(2));
            $('.buy__pay-result').text('К оплате: ' + cash.toFixed(2) + ' ' + this.valuta + '.');
        }
    }, {
        key: 'getUrlParameter',
        value: function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName = void 0,
                i = void 0;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? false : sParameterName[1];
                }
            }
        }
    }, {
        key: 'get_current_course_by_valuta',
        value: function get_current_course_by_valuta() {
            var dollar_euro_course = parseFloat($('.popup_donut').data('dollar_euro_course'));
            switch (this.valuta) {
                case '$':
                    return 1;
                    break;
                case '€':
                    return dollar_euro_course;
                    break;
            }
        }
    }]);

    return Donate;
}();