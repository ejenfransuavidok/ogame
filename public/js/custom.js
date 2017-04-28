// Event listenr

$(function(){

	var timer = setInterval(function(){
		if (window.theGame === undefined) return false;
		clearInterval(timer);
		setTimeout(function(){eventLoader()}, 0);
	}, 50);

	function eventLoader() {


		var $b = $('body');
		$b

			.on('click', function (e) {

				closest('.keep__item', 'is-open');
				closest('.js-dd-wrap', 'is-open');
				closest('.enter__form-footnote', 'is-open');
				closest('.enter__system-wrap', 'is-open');
				closest('.sselect-wrap', 'is-open');
				closest('.system__orbit-item .planet', 'is-open');


				if ( $('.aside').hasClass('aside_open') ){
					if ( !$(e.target).closest('.aside_open').size() && !$(e.target).closest('.js-dd-asidemenu').size() ) {
						$('.aside').removeClass('aside_open');
					}
				}



				function closest(el, cls){
					if ( $(el + '.' + cls).size() ){
						if ( !$(e.target).closest(el + '.' + cls).size() ) $(el + '.' + cls).removeClass(cls);
					}
				}

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

			.on('click', '.js-dd-asidemenu', function (e) {
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

			.on('click', '.keep__item-preview', function (e) {
				var $wrap = $(this).closest('.keep__item');

				$('.keep__item.is-open').not($wrap).removeClass('is-open');

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


            /*
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
			})
            */



			// popups

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

					if ( $t.find('.keep__item-info').size() ){
						$t.find('.keep__item').addClass('is-open');
					} else {
						$t.closest('.popup').find('.popup__pp').addClass('is-open');
					}

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

	}

});

(function(){
	var app = {
		module: {


			planetView: {

				data: {
					//three: '//cdnjs.cloudflare.com/ajax/libs/three.js/r73/three.min.js',
					three: 'https://threejs.org/build/three.min.js',
					selector: '.tjs-planet',
					inited: false,

					timer: null,

					options: {
						map: {
							size: 10,
							textures: {
								//map: '/img/planets3d/earth-map.jpg',
								//bumpMap: '/img/planets3d/earth-bump.jpg',
								//specularMap: '/img/planets3d/earth-specular.jpg'
							}
						},
						clouds: {
							size: 0.15,
							textures: {
								//map: '/img/planets3d/earth-clouds.jpg',
								//alphaMap: '/img/planets3d/earth-cloudstransperent.jpg'
							},
							glow: {
								size: 1,
								intensity: 0.6,
								fade: 3,
								color: 0x93cfef
							}
						}
					}

				},

				planet: function(options){

					var planet = new THREE.Object3D(),
						shader = options.main ? 32 : 128;


					// add map
					var mapGeometry = new THREE.SphereGeometry(options.map.size, shader, shader);
					var mapMaterial = new THREE.MeshPhongMaterial({
						bumpScale: 0.5,
						specular: new THREE.Color('grey'),
						shininess: 8
					});
					var map = new THREE.Mesh(mapGeometry, mapMaterial);
					map.name = 'map';
					planet.add(map);

					// add clouds
					if ( options.clouds.textures.map ){
						var cloudsGeometry = new THREE.SphereGeometry(options.map.size + options.clouds.size, shader, shader);
						var cloudsMaterial = new THREE.MeshPhongMaterial({
							side: THREE.DoubleSide,
							transparent: true,
							opacity: 0.8
						});
						var clouds = new THREE.Mesh(cloudsGeometry, cloudsMaterial);
						clouds.name = 'clouds';
						planet.add(clouds);
					}

					// add glow
					var glowGeometry = new THREE.SphereGeometry(options.map.size + options.clouds.size + options.clouds.glow.size, shader, shader);
					var glowMaterial = new THREE.ShaderMaterial({
						uniforms: {
							'c': {type: 'f',value: options.clouds.glow.intensity},
							'p': {type: 'f',value: options.clouds.glow.fade},
							glowColor: {type: 'c',value: new THREE.Color(options.clouds.glow.color)},
							viewVector: {type: 'v3',value: options.camera.position}
						},
						vertexShader: 'uniform vec3 viewVector;uniform float c;uniform float p;varying float intensity;void main() {vec3 vNormal = normalize( normalMatrix * normal );vec3 vNormel = normalize( normalMatrix * viewVector );intensity = pow( c - dot(vNormal, vNormel), p );gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );}',
						fragmentShader: 'uniform vec3 glowColor;varying float intensity;void main(){vec3 glow = glowColor * intensity;gl_FragColor = vec4( glow, 1.0 );}',
						side: THREE.BackSide,
						blending: THREE.AdditiveBlending,
						transparent: true
					});
					var glow = new THREE.Mesh(glowGeometry, glowMaterial);

					glow.name = 'glow';
					planet.add(glow);


					$.each( options.map.textures, function( key, value ) {
						var loader = new THREE.TextureLoader();
						loader.load(value, function(texture) {
							texture.minFilter = THREE.LinearFilter;
							mapMaterial[key] = texture;
							mapMaterial.needsUpdate = true;
							//console.info(mapMaterial[key]);

							options.container.style.opacity = 1;
						});
					});

					$.each( options.clouds.textures, function( key, value ) {
						var textureLoader = new THREE.TextureLoader();
						textureLoader.load(value, function(texture) {
							texture.minFilter = THREE.LinearFilter;
							cloudsMaterial[key] = texture;
							cloudsMaterial.transparent = true;
							cloudsMaterial.needsUpdate = true;
						});
					});


					return planet;

				},

				orbit: function(el, opts){

					var $el = $(el);
					var options = $.extend(true, {}, self.planetView.data.options, opts);

					options.container = $el[0];
					options.width = $el.width();
					options.height = $el.height();

					options.renderer = new THREE.WebGLRenderer({ alpha: true, antialiasing: true, logarithmicDepthBuffer: true });
					options.renderer.setSize(options.width * 2, options.height * 2);
					options.renderer.setClearColor( 0x000000, 0 );
					options.container.appendChild(options.renderer.domElement);

					options.renderer.domElement.style.width = '100%';
					options.renderer.domElement.style.height = '100%';

					options.camera = new THREE.PerspectiveCamera(45, options.width/options.height, 0.1, 1500);
					options.camera.position.set(0,0,29);


					options.planet = self.planetView.planet(options);


					options.light = new THREE.SpotLight(0xffffff);
					options.lightBack = new THREE.SpotLight(0xffffff, 0.1);

					if ( options.main ){
						options.light = new THREE.SpotLight(0xffffff, 0.5);
						options.lightBack = new THREE.SpotLight(0xffffff, 0.3);
						options.light.position.set(-100, 100, 100);
						options.lightBack.position.set(100, 0, 100);
					} else {
						options.light = new THREE.SpotLight(0xffffff);
						options.lightBack = new THREE.SpotLight(0xffffff, 0.1);
						options.light.position.set(-100, 0, 100);
						options.lightBack.position.set(1000, 0, 100);
					}

					options.scene = new THREE.Scene();
					options.scene.add(options.camera);
					options.scene.add(options.planet);
					options.scene.add(options.light);
					options.scene.add(options.lightBack);

					var render = function() {

						options.planet.getObjectByName('map').rotation.y += (options.map.speed / 1000);
						if ( options.planet.getObjectByName('clouds') ) options.planet.getObjectByName('clouds').rotation.y += (options.clouds.speed / 1000);
						requestAnimationFrame(render);
						options.camera.lookAt(options.planet.position);
						options.renderer.render(options.scene, options.camera);
					};

					render();


					$(window).resize(function(){
						if ( self.planetView.data.timer ) clearTimeout(self.planetView.data.timer);
						if ( self.planetView.data.inited ) self.planetView.timer = setTimeout(function(){self.planetView.resize(options)}, 200);
					});
				},

				galaxy: function (el) {

					var $el = $(el),
						container = $el[0],
						width = $el.width(),
						height = $el.height(),
						renderer = new THREE.WebGLRenderer({ alpha: true, antialiasing: true }),
						light,
						lightBack,
						scene,
						galaxy,
						camera;

					renderer.setSize(width * 2, height * 2);
					renderer.setClearColor( 0x000000, 0 );
					container.appendChild(renderer.domElement);

					renderer.domElement.style.width = '100%';
					renderer.domElement.style.height = '100%';

					camera = new THREE.PerspectiveCamera(45, width/height, 0.1, 1500);
					camera.position.set(0,0,29);

					//light = new THREE.SpotLight(0xffffff);
					//light.position.set(-100, 0, 100);

					//lightBack = new THREE.SpotLight(0xffffff, 0.1);
					//lightBack.position.set(1000, 0, 100);

					scene = new THREE.Scene();

					//scene.add(light);
					//scene.add(lightBack);

					var galaxyGeometry = new THREE.Geometry();

					for ( var i = 0; i < 10000; i ++ ) {

						var star = new THREE.Vector3();
						star.x = THREE.Math.randFloatSpread( 2000 );
						star.y = THREE.Math.randFloatSpread( 2000 );
						star.z = THREE.Math.randFloatSpread( 3000 );

						galaxyGeometry.vertices.push( star )

					}

					var galaxyMaterial = new THREE.PointsMaterial( { color: 0xffffff } );

					galaxy = new THREE.Points( galaxyGeometry, galaxyMaterial );

					scene.add( galaxy );

					/***/




					var render = function() {

						galaxy.rotation.y += 0.0002;
						requestAnimationFrame(render);
						renderer.render(scene, camera);
					};

					render();
				},

				resize: function(options){

					options.width = $(options.container).width();
					options.height = $(options.container).height();
					options.camera.aspect = options.width / options.height;
					options.camera.updateProjectionMatrix();
					options.renderer.setSize(options.width * 2, options.height * 2);
					options.renderer.domElement.style.width = '100%';
					options.renderer.domElement.style.height = '100%';
				},

				getData: function($el){
					var data = {};

					data.map = {};
					data.map.speed = parseInt($el.data('planet-mapspeed')) || 1;
					data.map.size = $el.data('planet-mapsize') ? (parseInt($el.data('planet-mapsize')) > 10 ? 10 : $el.data('planet-mapsize')) : 10;
					data.map.textures = {};
					data.map.textures.map = $el.data('planet-maptexturesmap');
					data.map.textures.bumpMap = $el.data('planet-maptexturesbumpmap');
					data.map.textures.specularMap = $el.data('planet-maptexturesspecularmap');

					data.clouds = {};
					data.clouds.speed = parseInt($el.data('planet-cloudsspeed')) || 2;
					data.clouds.textures = {};
					data.clouds.textures.map = $el.data('planet-cloudstexturesmap');
					data.clouds.textures.alphaMap = $el.data('planet-cloudstexturesalphamap');
					data.clouds.glow = {};
					data.clouds.glow.color =  '#555555';

					data.space =  $el.data('planet-space') || false;

					return data;
				},

				init: function(){
					var $pl = $(self.planetView.data.selector);

					if ( $pl.size() ){
						$.getScript(self.planetView.data.three, function(){
							self.planetView.data.inited = true;
							//game__visual
							$pl.each(function(){
								var $t = $(this), data = self.planetView.getData($t);

								if ( $t.hasClass('game__visual') ){
									data.clouds.glow.intensity = 0.9;
									data.clouds.glow.fade = 10;
									data.main = true;
									data.map.size = 10.5;
									data.clouds.glow.size = 0.5;
									data.clouds.glow.fade = 6;
								}

								if ( !$t.hasClass('is-render')){
									$t.addClass('is-render');
									self.planetView.orbit($t, data);
								}

							});


							$('.game__galaxy').each(function(){
								var $t = $(this);

								if ( !$t.hasClass('is-render')){
									$t.addClass('is-render');
									self.planetView.galaxy($t);
								}

							});

						});
					}

				}
			},

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

			fsRem: {

				data: {
					init: false,
					fs: 100,
					minWidth: 1360,
					minHeight: 600,
					currentWidth: 1920,
					currentHeight: 950,
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
								onCreate: function(){
									$(this).addClass('inited');
								},
								onScrollStart : function(){
									if (!$(this).closest('.tooltip').size() ) $('.tooltip.is-open').removeClass('is-open');
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
					theGame.fleetRangeSlider.init();
					self.scrollBar.init('.js-scroll-wrap', 'v');
					self.scrollBar.init('.js-scroll-hor', 'h');
				}
			}

		},

		init: function() {

			//self.viewPort.init();
			self.popup.init();
			self.fsRem.init();
			self.tooltip.init();
			self.plugs.update();
			self.tabs.init();
			self.planetView.init();
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



