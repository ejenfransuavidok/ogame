// Event listenr

$(function(){

	var timer = setInterval(function(){
		if (window.theGame === undefined) return false;
		clearInterval(timer);
		setTimeout(function(){eventLoader()}, 0);
	}, 50);

	function eventLoader() {


		var $b = $('body'),
			satelliteTimer = [],
			isTouch = (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));

		$b

			.on('click', function (e) {

				//closest('.keep__item', 'is-open');
				closest('.js-dd-wrap', 'is-open');
				closest('.enter__form-footnote', 'is-open');
				closest('.enter__system-wrap', 'is-open');
				closest('.sselect-wrap', 'is-open');
				closest('.system__orbit-item .planet', 'is-open');

				closest('.storage__item', 'show-plate');
				closest('.form__select', 'is-open');
				closest('.chat-content__head-menu', 'is-open');


				if ( $('.aside').hasClass('aside_open') ){
					if ( !$(e.target).closest('.aside_open').size() && !$(e.target).closest('.js-toggle-asidemenu').size() ) {
						$('.aside').removeClass('aside_open');
					}
				}

				function closest(el, cls){
					if ( $(el + '.' + cls).size() ){
						if ( !$(e.target).closest(el + '.' + cls).size() ) $(el + '.' + cls).removeClass(cls);
					}
				}

			})



			// v new


			.on('click', '.storage__item', function(e){
				e.preventDefault();
				var $t = $(this);
				if ( !$t.hasClass('show-plate') && $t.find('.storage__item-plate').length ){
					$('.storage__item').not($t).removeClass('show-plate');
					$t.addClass('show-plate');
				}
			})

			.on('click', '.storage__item-plate-close', function(e){
				e.stopPropagation();
				$(this).closest('.storage__item').removeClass('show-plate');
			})

			.on('mousemove', '.game__satellite-item', function(e){
				var $t = $(this);

				if ( satelliteTimer[$t.index()] ){
					clearTimeout(satelliteTimer[$t.index()]);
					satelliteTimer[$t.index()] = null;
				}

				satelliteTimer[$t.index()] = setTimeout(function () {
					$t.addClass('is-hover').closest('.game__satellite').addClass('in-hover')
				}, 20);

			})

			.on('mouseover', '.game__satellite-item', function(e){
				var $t = $(this);

			})

			.on('mouseleave', '.game__satellite-item', function(e){
				var $t = $(this);

				if (satelliteTimer[$t.index()]) clearTimeout(satelliteTimer[$t.index()]);

				$t.removeClass('is-hover').closest('.game__satellite').removeClass('in-hover')
			})



			.on('click', '.form__select-value, .form__select-handler', function(e){
				var $t = $(this),
					$select = $t.closest('.form__select');
				$select.toggleClass('is-open');
				$('.form__select').not($select).removeClass('is-open');
			})

			.on('click', '.form__select-item', function(e){
				var $t = $(this),
					$select = $t.closest('.form__select');

				$t.addClass('active').siblings().removeClass('active');
				$select.find('input').val($t.data('value'));
				$select.removeClass('is-open').find('.form__select-value').html($t.html());
			})


			.on('click', '.chat-content__head-handle', function(e){
				var $t = $(this);

				$t.closest('.chat-content__head-menu').toggleClass('is-open');

			})






			// ^ new
















			.on('click', '.game__history-item_message', function (e) {
				var $t = $(this);
				if ( !$t.hasClass('is-open') && !$t.hasClass('is-loading') ){
					$t.addClass('is-open');
				}
			})

			.on('click', '.game__history-close', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var $t = $(this);
				$t.closest('.game__history-item').removeClass('is-open');
			})

			.on('click', '.game__attack-item_attack, .game__attack-item_protect', function (e) {
				var $t = $(this);
				if ( !$t.hasClass('is-open') && !$t.hasClass('is-loading') ){
					$t.addClass('is-open');
				}
			})

			.on('click', '.game__attack-close', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var $t = $(this);
				$t.closest('.game__attack-item').removeClass('is-open');
			})


			.on('click', '.aside__menu-link', function (e) {
				var $t = $(this),
					$wrap = $t.closest('.aside__menu-item'),
					$submenu = $t.siblings('.aside__submenu');

				if ( $submenu.size() && !$submenu.hasClass('loading')){
					$submenu.addClass('loading');
					$wrap.toggleClass('is-open');
					$submenu.slideToggle(300, function(){
						$submenu.removeClass('loading');
					});
				}

			})

			.on('click', '.js-toggle-asidemenu', function (e) {
				$('.aside').toggleClass('aside_open');
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

			/*.on('click', '.keep__item-preview', function (e) {
				var $wrap = $(this).closest('.keep__item');

				$('.keep__item.is-open').not($wrap).removeClass('is-open');

				if ($wrap.find('.keep__item-info').size() ) $wrap.addClass('is-open');
			})*/
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
						})
						.find('.enter__form-field').removeClass('is-error');
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


			.on('click', '.sselect-current, .sselect-handle', function (e) {
				var $t = $(this),
					$wrap = $t.closest('.sselect-wrap');

				$wrap.toggleClass('is-open');

			})

			.on('click', '.sselect-item', function (e) {
				var $t = $(this),
					$wrap = $t.closest('.sselect-wrap'),
					$current = $wrap.find('.sselect-current'),
					$input = $wrap.find('input');


				$current.text($t.text());
				$input.val($t.data('value'));
				$wrap.removeClass('is-open');

			})



			.on('click', '.js-submit-form', function(e){
				e.preventDefault();
				$(this).closest('form').submit();
			})
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
						compvare: function (res) {

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
			})






			.on('click', '.planet__item-name-title', function(e){
				var $t = $(this);

				$t
					.closest('.planet__item')
					.find('.planet__item-all')
					.slideToggle(200, function(){
						$t.closest('.planet__item').toggleClass('is-open');
					})
				;
			})

			.on('click', '.force__item-name-title', function(e){
				var $t = $(this);

				$t
					.closest('.force__item')
					.find('.force__item-all')
					.slideToggle(200, function(){
						$t.closest('.force__item').toggleClass('is-open');
					})
				;
			})

			.on('click', '.popup__pp-close', function(e){
				$(this).closest('.popup__pp').removeClass('is-open');
			})


			.on('click', '.build__item', function(e){
				var $t = $(this);

				if ( !$t.hasClass('is-locked')){

					$t.closest('.popup').find('.popup__pp').addClass('is-open');

				}
			})


			.on('click', '.fleet__item-inner', function(e){
				var $t = $(this);

				if ( !$t.hasClass('is-locked')){

					$t.closest('.popup').find('.popup__pp').addClass('is-open');

				}
			})









			.on('click', '.system__orbit-item .planet__item', function(e){
				var $t = $(this),
					$p = $t.closest('.planet');

				$('.system__orbit-item .planet').not($p).removeClass('is-open');

				if ( !$p.hasClass('is-locked') && $p.find('.planet__info').size() ){
					$p.addClass('is-open');

				}
			})




		;







		/*(function nodes() {

			if ( !isTouch ){

				var $node1 = $('.game__planet-view'),
					$node2 = $('.game__planet-desk'),
					$win = $(window),
					winWidth = $win.width(),
					top = $win.scrollTop(),
					winHeight = $win.height();

				$b
					.on('mousemove', function(e){
					var x = e.pageX || 0,
					y = e.pageY || 0,
					deltaX = x - (winWidth / 2),
					deltaY = y - (winHeight / 2)
				;

				$node1.css({
					marginLeft: -deltaX / 200,
					marginTop: -deltaY / 200
				});

				$node2.css({
					marginLeft: -deltaX / 100,
					marginTop: -deltaY / 100
				});

			});


				$win.resize(function(){
					winWidth = $win.width();
				winHeight = $win.height();
			});

			}

		})();*/
			
			

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



			starWall: {

				init: function () {

					var $wall = $('.game__galaxy'),
						j = 0,
						max = 3000
					;

					if ( $wall.hasClass('is-init') ) return false;

					$wall.addClass('is-init');

					for (j; j < max; j++){


						var $star = $('<div/>', {
							class: 'game__galaxy-star',
							style: 'left: ' + getRandomPoint(0, 100) + '%; ' +
							'top: ' + getRandomPoint(0, 100) + '%; ' +
							'opacity: ' + getRandomPoint(0.1, 1) + ';' +
							'transform: scale(' + getRandomPoint(0.5, 2) + ')'
						}).appendTo($wall);


						if ( j === max - 1 ){


							// setInterval(function () {
							// 	var $s = $wall.find('.game__galaxy-star').eq(Math.round(getRandomPoint(0, max-1)));
							//
							// 	console.info($s);
							//
							// 	$s.addClass('is-lightning');
							//
							// 	setTimeout(function () {
							// 		$s.removeClass('is-lightning');
							// 	}, 1000);
							//
							// }, 1000)
						}
					}





					function getRandomPoint(min, max) {
						return Math.random() * (max - min) + min;
					}

				}
			},


			fsRem: {

				data: {
					init: false,
					fs: 100,
					minWidth: 1280,
					minHeight: 600,
					currentWidth: 1280,
					currentHeight: 800,
					screenWidth: 0,
					screenHeight: 0,
					timer: null
				},

				calc: function(){
					var $screen = $(window);
					var $html = $('html');
					var multiplier, newFontSize;

					self.fsRem.data.screenHeight = $screen.height() >= self.fsRem.data.minHeight ? $screen.height() : self.fsRem.data.minHeight;
					self.fsRem.data.screenWidth = $screen.width() >= self.fsRem.data.minWidth ? $screen.width() : self.fsRem.data.minWidth;

					if ( self.fsRem.data.screenWidth/self.fsRem.data.screenHeight <= self.fsRem.data.currentWidth/self.fsRem.data.currentHeight ){
						multiplier = self.fsRem.data.screenWidth / self.fsRem.data.currentWidth;
						newFontSize = multiplier * self.fsRem.data.fs;
					} else {
						multiplier = self.fsRem.data.screenHeight / self.fsRem.data.currentHeight;
						newFontSize = multiplier * self.fsRem.data.fs;
					}

					$html.css({fontSize: newFontSize+'%'});

				},

				init: function(){
					self.fsRem.data.init = true;
					var $screen = $(window);
					self.fsRem.calc();
					$screen.resize(function(){
						if ( self.fsRem.data.timer ) clearTimeout(self.fsRem.data.timer);
						if ( self.fsRem.data.init ) self.fsRem.data.timer = setTimeout(self.fsRem.calc, 200);
					});
				},

				stop: function(){
					self.fsRem.data.init = false;
					clearTimeout(self.fsRem.data.timer);
					self.fsRem.data.timer = null;
					$('html').removeAttr('style');
				}
			},

			fleetRangeSlider: {

				init: function(){

					var $slider = $('.range__slider');

					$slider.each(function(){
						var $t = $(this),
							$wrap = $t.closest('.range'),
							$counter = $wrap.find('.counter'),
							slider = $t[0],
							rMax = $t.data('range-max'),
							rStart = +$counter.find('.counter__value').text() * $t.data('range-max') / 100;

						if ( !$t.hasClass('is-inited')){
							$t.addClass('is-inited')

							$wrap.find('.range__current').html(rStart + ' ' + $t.data('range-val'));
							$wrap.find('.range__max').html($t.data('range-max') + ' ' + $t.data('range-val'));

							noUiSlider.create(slider, {
								margin: 1,
								step: 1,
								start: rStart,
								connect: [true, false],
								range: {
									'min': 0,
									'max': rMax
								}
							});

							slider.noUiSlider.on('change', function ( values, handle ) {
							});

							slider.noUiSlider.on('slide', function ( values, handle ) {
								$wrap.find('.range__current').html(parseInt(values[0]) + ' ' + $t.data('range-val'));
								$counter.find('.counter__value').text(parseInt((100 * values[0])/ rMax));
							});

							$counter.on('click', '.counter__up', function(){
								var val = +$counter.find('.counter__value').text();

								if ( val < 100 ){
									val++;
									setValue(val);
								}


							}).on('click', '.counter__down', function(){
								var val = +$counter.find('.counter__value').text();

								if ( val > 0 ){
									val--;
									setValue(val);
								}

							});

							function setValue(val){

								$counter.find('.counter__value').text(val);

								var range = rMax / 100 * val;
								$wrap.find('.range__current').html(parseInt(range) + ' ' + $t.data('range-val'));


								console.info($t.data('range-val'));
								$slider[0].noUiSlider.set([range]);
							}
						}


					});


				}
			},

			tooltip: {
				init: function(){
					var $tooltip = $('.tooltip');

					if ( !$tooltip.size() ){
						$tooltip = $('<div class="tooltip"><div class="tooltip__inner"><div class="tooltip__title"></div><div class="tooltip__text"><div class="tooltip__text-inner"></div></div></div></div>').appendTo('body');

						self.scrollBar.init($tooltip.find('.tooltip__text'), 'v');
					}

					$('body')


						.on('click', '[data-tooltip]', function(){
							var $t = $(this);
							var title = $t.attr('data-tooltip-title') || '';
							var text = $t.attr('data-tooltip-text') || '';

							$tooltip.find('.tooltip__title').html(title);
							$tooltip.find('.tooltip__text-inner').html(text);
							$tooltip.stop(true, true).delay(1000).addClass('is-open');

							var top = $t.offset().top + $t.outerHeight();
							var left = $t.offset().left + $t.outerWidth();
							$tooltip.css({top: top, left: left});

						})
						.on('click', function (e) {

							if ( $('.tooltip.is-open').size() ){
								if ( !$(e.target).closest('.tooltip.is-open').size() && !$(e.target).closest('[data-tooltip]').size() ) $('.tooltip.is-open').removeClass('is-open');
							}

						})
						.on('mousewheel', function (e) {

							$('.tooltip.is-open').removeClass('is-open');

						})
					;
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
						//$popup = self.popup.create(popup);

						console.error(popup + ' - Такого попапа не существует');
						return false;
					}
					if( html ){
						$popup.find('.popup__content').html(html);
					}
					$('body').addClass('overflow_hidden');

					$(window).trigger('resize');

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
						.on('mousedown', '.popup', function(e){
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
				destroy: function(el){
					if ( $(el).hasClass('inited') ){
						$(el).filter('.inited').removeClass('inited').mCustomScrollbar('destroy');
					}
				},

				update: function(el){
					if ( $(el).hasClass('inited') ){
						$(el).filter('.inited').removeClass('inited').mCustomScrollbar('update');
					}
				},

				init: function(el, v){

					if ( v == 'h'){
						$(el).not('.inited').mCustomScrollbar({
							axis:"x",
							advanced:{autoExpandHorizontalScroll:true},
							callbacks:{
								onCreate: function(){
									$(this).addClass('inited');
								},
								onScrollStart : function(){
									if (!$(this).closest('.tooltip').size() ) $('.tooltip.is-open').removeClass('is-open');
								}
							}
						});
					} else {
						$(el).not('.inited').mCustomScrollbar({
							callbacks:{
								whileScrolling: function(){
									scrollPos(this);
								},
								onCreate: function(){
									$(this).addClass('inited on-top');

									if ( $(this).hasClass('js-scroll-end')){
										$(this).mCustomScrollbar('scrollTo', 'bottom',{ scrollInertia: 0});
									}
								},
								onScrollStart : function(){
									if (!$(this).closest('.tooltip').size() ) $('.tooltip.is-open').removeClass('is-open');
								}
							}
						});
					}

					function scrollPos(t) {
						var $t = $(t);
						if ( t.mcs.topPct <= 1 ){
							$t.addClass('on-top')
						} else if ( t.mcs.topPct >= 99 ){
							$t.addClass('on-bottom')
						} else if ( $t.hasClass('on-top') || $t.hasClass('on-bottom')) {
							$t.removeClass('on-top on-bottom')
						}
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

							if( !$t.hasClass('is-active')){
								$links.removeClass('is-active');
								$t.addClass('is-active');
								$tabs.removeClass('is-active');
								$tabItem.addClass('is-active');

								$t.closest('.mCustomScrollbar').mCustomScrollbar("update");
							}

							function selectGroup(){
								return $(this).data('tab-group') === group;
							}

						})
					;
				}
			},

			plugs:{
				update: function(){
					self.fleetRangeSlider.init();
					self.scrollBar.init('.js-scroll-wrap', 'v');
					self.scrollBar.init('.js-scroll-hor', 'h');
				}
			}

		},

		init: function() {

			self.popup.init();
			self.fsRem.init();
			self.tooltip.init();
			self.plugs.update();
			self.tabs.init();
			self.starWall.init();

			//$('[data-required=phone]').mask('+7 (999) 999-99-99');

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



