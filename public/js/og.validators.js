'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Validator = exports.Validator = function () {
	function Validator() {
		var callback = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

		_classCallCheck(this, Validator);

		this.callback = callback;
	}

	_createClass(Validator, [{
		key: 'check_form',
		value: function check_form(in_form) {
			return this.iterate_fields(in_form);
		}
	}, {
		key: 'clear_fields',
		value: function clear_fields(in_form) {
			var i = void 0,
			    el = void 0,
			    type = void 0;
			for (i = 0; i < $(in_form).find('input').length; i++) {
				el = $(in_form).find('input')[i];
				type = $(el).attr('type');
				if (type != 'submit' && type != 'hidden') $(el).val('');
			}
		}
	}, {
		key: 'check_required',
		value: function check_required(field, compare) {
			var value = void 0,
			    form = void 0,
			    result = void 0;
			form = $(field).closest('form');
			if (typeof $(field).data('required') != 'undefined') {
				if ($(field).data('required') == compare) {
					value = $(field).val();
					switch (compare) {
						case true:
							result = value == '';
							break;
						case 'email':
							result = !this.validateEmail(value);
							break;
						case 'phone':
							result = value == '';
							break;
						case 'email-or-phone':
							result = !(this.validateEmail(value) || this.validatePhone(value));
							break;
						default:
							result = false;
							break;
					}
					if (result) {
						if (!$(field).parent().parent().hasClass('error')) $(field).parent().parent().addClass('error');
						var message = void 0;
						switch (compare) {
							case true:
								//$.notify ('Поле должно быть заполнено!', 'warn');
								message = 'Поле должно быть заполнено!';
								break;
							case 'email':
								//$.notify ('Поле должно содержать корректный email!', 'warn');
								message = 'Поле должно содержать корректный email!';
								break;
							case 'phone':
								//$.notify ('Поле "телефон" должно быть заполнено!', 'warn');
								message = 'Поле "телефон" должно быть заполнено!';
								break;
							case 'email-or-phone':
								//$.notify ('Поле должно содержать корректный телефон или E-Mail!', 'warn');
								message = 'Поле должно содержать корректный телефон или E-Mail!';
								break;
							default:
								//$.notify ('Поле должно быть заполнено!', 'warn');
								message = 'Поле должно быть заполнено!';
								break;
						}
						$.notify(message, 'warn');
						$(field).parent().next().text(message);
						return 'error';
					}
				}
			}
			$(field).parent().parent().removeClass('error');
			$(field).parent().next().text('');
			return 'good';
		}
	}, {
		key: 'iterate_fields',
		value: function iterate_fields(in_form) {
			var i = void 0,
			    el = void 0;
			for (i = 0; i < $(in_form).find('input').length; i++) {
				el = $(in_form).find('input')[i];
				if (this.check_field(el) == 'error') return 'error';
			}
			return 'good';
		}
	}, {
		key: 'check_field',
		value: function check_field(field) {
			this.callback = this.callback ? this.callback : this.check_required;
			if (this.callback(field, true) == 'error') return 'error';
			if (this.callback(field, 'email') == 'error') return 'error';
			if (this.callback(field, 'phone') == 'error') return 'error';
			if (this.callback(field, 'email-or-phone') == 'error') return 'error';
			return 'good';
		}
	}, {
		key: 'validateEmail',
		value: function validateEmail(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email.trim());
		}
	}, {
		key: 'validatePhone',
		value: function validatePhone(phone) {
			var re = /^((8|\+7)-?)?\(?\d{3}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}$/;
			return re.test(phone.trim());
		}
	}]);

	return Validator;
}();