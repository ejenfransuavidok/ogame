// Event listenr

$(function(){

	var timer = setInterval(function(){
		if (window.theGame === undefined) return false;
		clearInterval(timer);
		setTimeout(function(){eventLoader()}, 0);
	}, 50);

	function eventLoader() {


		$('body')

			.on('click', function (e) {

				closest('.keep__item');
				closest('.js-dd-wrap');
				closest('.enter__form-footnote');
				closest('.enter__system-wrap');

				function closest(el){
					if ( $(el + '.is-open').size() ){
						if ( !$(e.target).closest(el + '.is-open').size() ) $(el + '.is-open').removeClass('is-open');
					}
				}

			})


			.on('click', '.js-dd-link', function (e) {
				var $wrap = $(this).closest('.js-dd-wrap');
				$('.js-dd-wrap').not($wrap).removeClass('is-open');
				$wrap.toggleClass('is-open');
			})
			.on('click', '.is-compact', function (e) {
				$(this).removeClass('is-compact');
			})
			.on('click', '.game__force-close', function (e) {
				$(this).closest('.game__force-group').addClass('is-compact');
			})
			.on('click', '.game__menu-close', function (e) {
				$(this).closest('.game__menu-item').addClass('is-compact');
			})

			.on('click', '.keep__item-preview', function (e) {
				var $wrap = $(this).closest('.keep__item');

				$(this).closest('.keep').find('.is-open').not($wrap).removeClass('is-open');

				if ($wrap.find('.keep__item-info').size() ) $wrap.addClass('is-open');
			})
			.on('click', '.keep__item-info-close', function (e) {
				$(this).closest('.keep__item').removeClass('is-open');
			})






			.on('click', '.game__login-form', function (e) {
				var $form = $(this);

				if ( $form.hasClass('is-closed') ){
					$form
						.addClass('is-open')
						.find('.enter__form')
						.slideDown(300, function(){
							$(this).closest('.game__login-form').removeClass('is-closed');
						});

					$form
						.siblings('.is-open')
						.removeClass('is-open')
						.find('.enter__form')
						.slideUp(300, function(){
							$(this).closest('.game__login-form').addClass('is-closed');
						});
				}
			})

			.on('click', '.enter__close', function (e) {
				var $form = $(this).closest('.game__login-form');

				if ( $form.hasClass('is-open') ){
					$form
						.removeClass('is-open')
						.find('.enter__form')
						.slideUp(300, function(){
							$(this).closest('.game__login-form').addClass('is-closed');
						});
				}
			})

			.on('mousedown', '.enter__form-controls-clear', function (e) {
				var $input = $(this).closest('.enter__form-input').find('input');

				$input.val('');
				setTimeout(function(){
					$input.focus()
				}, 0);
			})





			.on('click', '.enter__form-footnote-link', function (e) {
				var $t = $(this),
					$wrap = $t.closest('.enter__form-footnote'),
					$tooltip = $wrap.find('.enter__form-footnote-tooltip');

				if ( $tooltip.size() ){
					e.preventDefault();
					$wrap.toggleClass('is-open');
				}
			})




			.on('click', '.enter__system-current, .enter__system-handle', function (e) {
				var $t = $(this),
					$wrap = $t.closest('.enter__system-wrap');

				$wrap.toggleClass('is-open');

			})



			.on('click', '.enter__system-item', function (e) {
				var $t = $(this),
					$wrap = $t.closest('.enter__system-wrap'),
					$current = $wrap.find('.enter__system-current'),
					$input = $wrap.find('input');


				$current.text($t.text());
				$input.val($t.data('value'));
				$wrap.removeClass('is-open');

			})



			.on('click', '.js-submit-form', function(e){
				e.preventDefault();
				$(this).closest('form').submit();
			})
            /*
			.on('submit', '.game__login-form', function(e){
				e.preventDefault();
				var $form = $(this);


				$form
					.find('.enter__form-field')
					.removeClass('is-error is-success')
					.find('.enter__form-message')
					.html('');

				if (theGame.checkForm($form)) {

					$.ajax({
						type: $form.attr('method'),
						url: $form.attr('action'),
						data: $form.serialize(),
						complete: function (res) {

							var data = JSON.parse(res.responseText);


							if (data.errors.length){

								var i = 0;

								for (i; i<data.errors.length; i++){

									var $input = $form.find('[name="' + data.errors[i].name + '"]'),
										$field = $input.closest('.enter__form-field');

									$field
										.addClass('is-error')
										.find('.enter__form-message')
										.html(data.errors[i].message);
								}

							}


						}
					});
				}
			})*/
		;

	}

});

(function(){
	var app = {
		module: {

			checkForm: function(form){
				var $el = $(form),
					wrong = false;

				$el.find('[data-required]').each(function(){
					var $t = $(this),
						type = $t.data('required'),
						$wrap = $t.closest('[data-form=field]'),
						$mes = $wrap.find('[data-form=message]'),
						val = $.trim($t.val()),
						errMes = '',
						rexp = /.+$/igm;

					$wrap.removeClass('is-error');
					$mes.html('');

					if ( $t.attr('type') == 'checkbox' && !$t.is(':checked') ) {
						val = false;
					} else if ( /^(#|\.)/.test(type) ){
						if ( val !== $(type).val() || !val ) val = false;
					} else if ( /^(name=)/.test(type) ){
						if ( val !== $('['+type+']').val() || !val ) val = false;
					} else if ( $t.attr('type') == 'radio'){
						var name =  $t.attr('name');
						if ( $('input[name='+name+']:checked').length < 1 ) val = false;
					} else {
						switch (type) {
							case 'number':
								rexp = /^\d+$/i;
								errMes = 'Поле должно содержать только числовые символы';
								break;
							case 'phone':
								rexp = /[\+]\d\s[\(]\d{3}[\)]\s\d{3}\s\d{2}\s\d{2}/i;
								break;
							case 'letter':
								rexp = /^[A-zА-яЁё]+$/i;
								break;
							case 'rus':
								rexp = /^[А-яЁё]+$/i;
								break;
							case 'email':
								rexp = /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/i;
								errMes = 'Проверьте корректность email';
								break;
							case 'password':
								rexp = /^(?=^.{8,}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/;
								errMes = 'Слишком простой пароль';
								break;
							default:
								rexp = /.+$/igm;
						}
					}


					if ( !rexp.test(val) || val == 'false' || !val ){
						wrong = true;
						$wrap.addClass('is-error');

						if (!val) errMes = 'Поле не заполнено';

						if ( errMes ){
							$mes.html(errMes);
						}

					} else {
						//$wrap.addClass('is-success');

					}

				});


				return !wrong;

			},

			viewPort: {

				current: '',

				data: {
					'0': function(){
						self.viewPort.current = 0;
						console.info('-----mobile-----');
					},
					'640': function(){
						self.viewPort.current = 1;
						console.info('-----tabletVer-----');
					},
					'960': function(){
						self.viewPort.current = 2;
						console.info('-----tabletHor-----');
					},
					'1200': function(){
						self.viewPort.current = 3;
						console.info('-----desktop-----');
					},
					'1500': function(){
						self.viewPort.current = 4;
						console.info('-----desktop HD-----');
					},
					'1800': function(){
						self.viewPort.current = 5;
						console.info('-----full HD-----');
					}
				},

				init: function(data){
					var points = data || self.viewPort.data;
					if ( points ){
						points['Infinity'] = null;
						var sbw = scrollBarWidth(), curPoint = null;
						var ww = $(window).width() + sbw;
						checkCurrentViewport();
						$(window).on('resize', function(){
							ww = $(window).width() + sbw;
							checkCurrentViewport();
						});
					}

					function checkCurrentViewport(){
						var pp = 0, pc = null;
						$.each(points, function(point, callback){
							if ( point > ww ){
								if ( pp !== curPoint ) {
									curPoint = pp;
									pc();
								}
								return false;
							}
							pp = point; pc = callback;
						});
					}

					function scrollBarWidth(){
						var scrollDiv = document.createElement('div');
						scrollDiv.className = 'scroll_bar_measure';
						$(scrollDiv).css({
							width: '100px',
							height: '100px',
							overflow: 'scroll',
							position: 'absolute',
							top: '-9999px'
						});
						document.body.appendChild(scrollDiv);
						sbw = scrollDiv.offsetWidth - scrollDiv.clientWidth;
						document.body.removeChild(scrollDiv);
						return sbw;
					}

				}
			},



			tooltip: {
				init: function(){
					var $tooltip = $('.tooltip');

					if ( !$tooltip.size() ){
						$tooltip = $('<div/>', {'class': 'tooltip'}).appendTo('body');
					}

					$('body').on('mouseover', '[data-tooltip]', function(){
						var $t = $(this);
						var text = $t.attr('data-tooltip');
						$tooltip.html('<span class="tooltip-inner">'+text+'</span>').stop(true, true).delay(1000).addClass('show');

						var top = $t.offset().top + $t.outerHeight();
						var left = $t.offset().left + ($t.outerWidth()/2);
						$tooltip.css({top: top, left: left});

					}).on('mouseleave', '[data-tooltip]', function(){
						$tooltip.removeClass('show');
					});


				}
			},

			popup: {
				create: function(popup){
					var pref = popup.indexOf('#') == 0  ? 'id' : 'class';
					var name = popup.replace(/^[\.#]/,'');
					var $popup = $('<div class="popup">'
						+			'<div class="popup__inner">'
						+				'<div class="popup__layout">'
						+					'<div class="popup__close"></div>'
						+					'<div class="popup__content"></div>'
						+				'</div>'
						+			'</div>'
						+			'<div class="popup__overlay"></div>'
						+		'</div>').appendTo('body');

					if ( pref == 'id'){
						$popup.attr(pref, name);
					} else {
						$popup.addClass(name);
					}

					return $popup;
				},

				open: function(popup, html){
					var $popup = $(popup);
					if (!$popup.size()){
						$popup = self.popup.create(popup);
					}
					if( html ){
						$popup.find('.popup__content').html(html);
					}
					$('body').addClass('overflow_hidden');
					return $popup.show();
				},

				close: function(popup){
					var $popup = $(popup);
					$('body').removeClass('overflow_hidden');
					$popup.hide();
				},

				info: function(mes, callback){
					var html = '<div class="popup__text">' + mes + '</div>'
						+	'<div class="popup__link"><span class="btn btn_green">Ок</span></div>';
					self.popup.open('.popup_info', html);

					$('.popup_info').find('.btn').click(function(){
						self.popup.close($('.popup_info'));
						if ( callback ) callback();
					});
				},

				remove: function(popup){
					var $popup = $(popup);
					$('body').removeClass('overflow_hidden');
					$popup.remove();
				},

				init: function(){
					$('body')
						.on('click', '.popup', function(e){
							if ( !$(e.target).closest('.popup__layout').size() ) self.popup.close('.popup');
						})
						.on('click', '.popup__close, .js-popup-close', function(e){
							e.preventDefault();
							e.stopPropagation();
							self.popup.close($(this).closest('.popup'));
						})
						.on('click', '[data-popup]', function(e){
							e.preventDefault();
							e.stopPropagation();
							self.popup.close('.popup');
							self.popup.open($(this).data('popup'));
						})
					;
				}
			},

			scrollBar: {

				init: function(el){
					$(el).not('.inited').mCustomScrollbar({
						callbacks:{
							onCreate: function(){
								$(this).addClass('inited');
							}
						}
					});
				}
			}

		},

		tabs: {
			init: function(){
				$('body')
					.on('click', '[data-tab-link]', function(e){
						/**
						 * data-tab-link='name_1', data-tab-group='names'
						 * data-tab-targ='name_1', data-tab-group='names'
						 **/
						e.preventDefault();
						var $t = $(this);
						var group = $t.data('tab-group');
						var $links = $('[data-tab-link]').filter(selectGroup);
						var $tabs = $('[data-tab-targ]').filter(selectGroup);
						var ind = $t.data('tab-link');
						var $tabItem = $('[data-tab-targ='+ind+']').filter(selectGroup);

						if( !$t.hasClass('active')){
							$links.removeClass('active');
							$t.addClass('active');
							$tabs.fadeOut(150);
							setTimeout(function(){
								$tabs.removeClass('active');
								$tabItem.fadeIn(150, function(){
									$(this).addClass('active');
								});
							}, 150)
						}

						function selectGroup(){
							return $(this).data('tab-group') === group;
						}

					})
				;
			}
		},

		init: function() {

			//self.viewPort.init();
			//self.preLoad.init();
			//self.tooltip.init();
			self.scrollBar.init('.js-scroll-wrap');
			//self.bottomStick.init('.js-bottom-stick');

			//$('[data-required=phone]').mask('+7 (999) 999-99-99');

			var $b = $('body');

			$b
				.on('click', '[data-scrollto]', function(e){
					e.preventDefault();
					var $el = $(this).data('scrollto');
					$('html,body').animate({scrollTop: $($el).offset().top}, 500);
				})

			;
		}
	};
	var self = {};
	var loader = function(){
		self = app.module;
		window.theGame = app.module;
		app.init();
	};
	var ali = setInterval(function(){
		if (typeof jQuery !== 'function') return;
		clearInterval(ali);
		setTimeout(loader, 0);
	}, 50);

})();



