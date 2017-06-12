var myapp;
var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name'
      };
function Foreach (dictionary, action) {
	for (k in dictionary) {
		action (k, dictionary [k]);
	}
};
/*
function Find (dictionary, action) {
	var res = 0;
	for (k in dictionary) {
		var val = dictionary [k];
		res = action (res, val) ? res : val;
	}
	return res;
};
*/
(function(){
	myapp = {
		init: function () {
            try{
                var getUrl = window.location;
                var baseUrl = getUrl .protocol + "//" + getUrl.host + "" + getUrl.pathname.split('/')[1];
                this.socket = io(baseUrl+':8000');            
                this.socket.on('synchro', function(data){
                    /**
                     * @ определим выбранную дату доставки
                     */
                    var selected = $('[data-name=date].active')[0];
                    var next = $(selected).next('[data-name=date]');
                    while($(next).hasClass('disable')){
                        next = $(next).next('[data-name=date]');
                    }
                    var selectedday = parseInt($(selected).data('day'));
                    var nextday = parseInt($(next).data('day')); 
                    var date = new Date();
                    var todayday = parseInt(date.getDate());
                    if(/*selectedday == todayday + 1*/true){
                        if(!$(selected).hasClass('disable'))
                            $(selected).addClass('disable');
                        $(next).trigger('click');
                        console.log('selected: ' + selectedday + ' today: ' + todayday + ' nextday: ' + nextday);
                        console.log('TRUE');
                    }
                    else{
                        console.log('selected: ' + selectedday + ' today: ' + todayday + ' nextday: ' + nextday);
                        console.log('FALSE');
                    }
                })
            }    
            catch(err) {
                $.notify (err.message, 'error');
            }
            this.days = { 1: 'день', 2: 'дня', 3: 'дня' };
            this.deliveries = { 1: 'доставка', 2: 'доставки', 3: 'доставки', 4: 'доставки', 5: 'доставок', 6: 'доставок', 7: 'доставок', 8: 'доставок', 9: 'доставок', 10: 'доставок', 11: 'доставок', 12: 'доставок', 13: 'доставок', 14: 'доставок', 15: 'доставок' };
            
			this.order_service.init ();
            this.total_price = 0;
            this.deliveryPrice = 0;
            this.mymap.kmlLayer = [];
            
			try {
                this.yametriks.init();
				this.mymap.init ();
				this.order_during.init ();
                this.order_when_will_feed_properly.init ();
				this.calc_auto_tab.init ();
				/// обновление токена
				this.order_service.refresh_token ();
				/// обновление списка продуктов
				this.order_service.refresh_products_list ();
                /// запуск сервера времени
                /// this.time.init ();
                /// кнопки меню
                this.buttons.init ();
                /// команда
                this.team.init ();
                /// модальные окна
                this.modals.result.init ();
                // информационные окна
                this.windows.init ();
                // реакция форм на некоторые события
                this.forms.init ();
			}
			catch(err) {
				$.notify (err.message, 'error');
				console.log ( err.message );
			}
		},
        yametriks: {
            init: function () {
                if($('[href=#popupOrder]').length) {
                    $(document).on('click', '[href=#popupOrder]', function(evt) {
                        yaCounter41932619.reachGoal('BUTTON_ORDER_CLICK');
                    });
                }
                if($('[data-ygoal]').length) {
                    $(document).on('click', '[data-ygoal]', function(evt) {
                        yaCounter41932619.reachGoal($(this).data('ygoal'));
                    });
                }
            },
        },
        forms: {
            init: function () {
                $(document).on('keyup', 'form input', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                        var inputs = $(this).closest('form').find(':input');
                        inputs.eq( inputs.index(this)+ 1 ).focus();
                        return false;
                    }
                });
                $(document).on('keypress', 'form input', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                        return false;
                    }
                });
            },
        },
        callbacks: {
            launch: function () {
                console.log (myapp.callback);
                try {
                    if (myapp.callback) {
                        eval (myapp.callback);
                        myapp.callback = false;
                    }
                } catch (err) {
                    console.log ( err.message );
                }
            },
        },
        windows: {
            init: function () {
                $(document).on ('click', '[data-window]', function (ev) {
                    var self = this, 
                        selector = $(self).data ('window'), 
                        callback = typeof $(self).data ('callback') != 'undefined' ? $(self).data ('callback') : false;
                    myapp.callback = callback;
                    myapp.modals.open (selector);
                });
                $('[data-class=window] > .popup-inner > .popup-layout > .popup-close, .close-popup').off ();
                $(document).on ('click', '[data-class=window] > .popup-inner > .popup-layout > .popup-close, .close-popup', function (ev) {
                    myapp.modals.close ();
                });
            },
        },
        team: {
            init: function () {
                $(document).on ('click', '[data-team_icon]', function (ev) {
                    var data_team_icon = $(this).data ('team_icon');
                    var data_team_text = $('[data-team_text='+data_team_icon+']').first ();
                    var data_team_bg = $('[data-team_bg='+data_team_icon+']').children ( 'img' ).first ();
                    $.each ($('[data-team_icon='+data_team_icon+']'), function (index, element) {
                        $(element).attr ( 'src', $(element).data ( 'gray' ) );
                    });
                    $(this).attr ( 'src', $(this).data ( 'active' ) );
                    $(data_team_text).text ( $(this).attr ('alt') );
                    $(data_team_bg).attr ( 'src', $(this).data ( 'gray' ) );
                });
            },
        },
        time: {
            init: function () {
                // каждые 10 секунд
                setInterval(function() {
                    var currentdate = new Date();
                    var hours = currentdate.getHours();
                    var minutes = currentdate.getMinutes();
                    var seconds = currentdate.getSeconds();
                    /**
                     * @ перезагрузим страницу если попали в интервал 10:59:50 - 11:00:10
                     */
                    if((hours == 10 && minutes == 59 && seconds >= 50)||(hours == 11 && minutes == 0 && seconds <= 10)){
                        location.reload();
                        return true;
                    }
                    var datetime = "Last Sync: " + currentdate.getDate() + "/"
                                    + (currentdate.getMonth()+1)  + "/" 
                                    + currentdate.getFullYear() + " @ "  
                                    + currentdate.getHours() + ":"  
                                    + currentdate.getMinutes() + ":" 
                                    + currentdate.getSeconds();
                    var selected = $('[data-day]').siblings ('.active').first ();
                    var day = parseInt ($(selected).data ('day'));
                    if (currentdate.getHours() > 13 && currentdate.getDate() + 1 == day) {
                        // если время больше порогового - 13-00, то активируем следующую доступную дату доставки
                        var number = 0, done = false;
                        $('[data-day]').each (function (number, element) {
                            // отключаем активный
                            if ($(element).hasClass ('active') && !done) {
                                $(element).toggleClass ('active');
                                $(element).addClass ('disable');
                                number = $(element).data ('day');
                            }
                            if ( number && !$(element).hasClass ('active') && !$(element).hasClass ('disable') && !done ) {
                                $(element).toggleClass ('active');
                                done = true;
                            }
                        });
                    }
                    //console.log (datetime);
                }, 10000);
            },
        },
		calc_auto_tab: {
			init: function () {
				this.age_keyup_counter = 0;
				this.height_keyup_counter = 0;
				$(document).on ('keyup', '[name=age]', function (ev) {// console.log (isNaN(ev.key));
					myapp.calc_auto_tab.switcher (ev.key, 'age', $(this), $('[name=height]'));
				})
				.on ('keyup', '[name=height]', function (ev) {
					myapp.calc_auto_tab.switcher (ev.key, 'height', $(this), $('[name=weight]'));
				});
			},
			switcher: function (key, input_name, element, switch_to) {
				switch (input_name) {
					case 'age':
						myapp.calc_auto_tab.age_keyup_counter = $(element).val ().length;
						if (isNaN(key)) {
							myapp.calc_auto_tab.age_keyup_counter = myapp.calc_auto_tab.age_keyup_counter > 0 ? myapp.calc_auto_tab.age_keyup_counter - 1 : 0;
							return;
						}
						else if (myapp.calc_auto_tab.age_keyup_counter > 2) {
							myapp.calc_auto_tab.age_keyup_counter = 2;
							$(element).val ( $(element).val ().slice (0, 2) );
							$(switch_to).focus ();
							return;
						}
						else if (myapp.calc_auto_tab.age_keyup_counter == 2) {
							$(switch_to).focus ();
						}
						break;
					case 'height':
						myapp.calc_auto_tab.height_keyup_counter = $(element).val ().length;
						if (isNaN(key)) {
							myapp.calc_auto_tab.height_keyup_counter = myapp.calc_auto_tab.height_keyup_counter > 0 ? myapp.calc_auto_tab.height_keyup_counter - 1 : 0;
							return;
						}
						else if (myapp.calc_auto_tab.height_keyup_counter > 3) {
							myapp.calc_auto_tab.height_keyup_counter = 3;
							$(element).val ( $(element).val ().slice (0, 3) );
							$(switch_to).focus ();
							return;
						}
						else if (myapp.calc_auto_tab.height_keyup_counter == 3) {
							$(switch_to).focus ();
						}
						break;
				}
			}
		},
		order_during: {
			init: function () {
				$(document).on ('click', '[data-role=day]', function (ev) {
                    var day = $(this).data('value');
                    var date = $('[data-name=date]').siblings('.active').first();
                    var date_d = parseInt($(date).data('day'));
                    var date_m = parseInt($(date).data('month'));
                    var date_y = parseInt($(date).data('year'));
                    var deliverydate = new Date();
                    deliverydate.setFullYear(date_y);
                    deliverydate.setMonth(date_m - 1);
                    deliverydate.setDate(date_d);
                    // Если день, то нельзя менять день
                    var during = $('[data-name=duration].active').first ();
                    if ($(during).data ( 'value' ) == 'day')
                        return false;
                    // Если 3 дня и не четверг - то не меняем
                    if( $(during).data ( 'value' ) == '3days' && deliverydate.getDay() != 4)
                        return false;
                    // Если 3 дня и четверг доставка и кликнули субботу или понедельник
                    if($(during).data ( 'value' ) == '3days'){
                        if(deliverydate.getDay() == 4 && (day == 6 || day == 1)) {
                            $('[data-role=day]').siblings('[data-value=1]').toggleClass('active');
                            $('[data-role=day]').siblings('[data-value=6]').toggleClass('active');
                            return myapp.order_during.refresh ();
                        }
                        else{
                            return myapp.order_during.refresh ();
                        }
                    }
                    // выключенное не трогаем
                    if ($(this).hasClass('disable')) return false;
                    // будние дни не изменяем
                    if ( $(this).data ('value') < 6 ) return false;
                    
                    // Если выбираем воскресенье и суббота не выбрана, выбираем и субботу
                    if ( ! $('[data-value=6]').hasClass ('active') && ! $('[data-value=7]').hasClass ('active') && $(this).data ('value') == 7 ) {
                        $('[data-value=6]').addClass ( 'active' );
                        // дублируем воскресенье - если две недели
                        $('[data-value=7]').addClass ( 'active' );
                        return myapp.order_during.refresh ();
                    }
                    
                    // Если суббота активна и воскресенье активно и клик по субботе, то делаем неактивным воскресенье
                    if ( $(this).data ('value') == 6 && $(this).hasClass('active') && $('[data-value=7]').hasClass('active') ){
                        $('[data-value=7]').removeClass ( 'active' );
                        return myapp.order_during.refresh ();
                    }
                    // Если суббота активна и воскресенье неактивно и клик по субботе, то делаем неактивным субботу
                    else if ( $(this).data ('value') == 6 && $(this).hasClass('active') && !$('[data-value=7]').hasClass('active') ){
                        $('[data-value=6]').removeClass ( 'active' );
                        return myapp.order_during.refresh ();
                    }
                    
					var active = $(this).hasClass ('active') ? -1 : 1;
					var count_toggled_is = $('[data-role=day]').siblings ('.active').length;
					var count_toggled_will_be = count_toggled_is + active;
					var week_or_more = $('[data-value=week]').hasClass ('active') || $('[data-value=2week]').hasClass ('active');
					// в неделе не может быть менее 5 дней
					if (week_or_more && count_toggled_will_be < 5) {
						return myapp.order_during.refresh ();
					}
					else if (week_or_more) {
						//$(this).toggleClass('active');
                        $('[data-value='+day+']').toggleClass('active');
                    }
					else {
						// один день
						$(this).siblings ().removeClass ('active');
						$(this).addClass ('active');
					}
					myapp.order_during.refresh ();
				})
				.on ('click', '[data-role=during]', function (ev) {
					if ($(this).hasClass('disable')) return false;
					$(this).addClass('active').siblings().removeClass('active');
                    // здесь проще вызвать событие клика по активной дате, там же вызовется обновление
                    $('[data-name=date]').siblings('.active').first().trigger('click');
					//myapp.order_during.refresh ();
				})
				.on('click', '[data-name=energy]', function(e) {
					if ($(this).hasClass('disable')) return false;
					$(this).addClass('active').siblings().removeClass('active');
					myapp.order_during.refresh ();
				});
				myapp.order_during.refresh ();
			},
			refresh: function () {
				var energy = $('[data-role=energy]').siblings ('.active').first ().data ('value');
				var during = $('[data-role=during]').siblings ('.active').first ().data ('value');
				var days = $('[data-role=day]').siblings ('.active').length;
				var result_days = 0;
				switch (during) {
					case 'day':
						result_days = 1;
						break;
                    case '3days':
						result_days = parseInt (days);
						break;
					case 'week':
						result_days = parseInt (days);
						break;
					case '2week':
						result_days = parseInt (days);
						break;
				}
				$.ajax({
					type: 'post',
					url: '/index.php?id=20',
					data: 'energy='+energy+'&days='+result_days+'&'+$.myapp.order_service.getformdata (),
					dataType: "json",
					success: function (response) {
						$('.price-day').text (response ['for_day'] + ' р./день');
                        var price_all = '<p>Правильное питание: ' + response ['PRICE'] + 'р.</p>';
                        var days = myapp.days [ parseInt(response ['DELIVERY_DAYS']) ] != undefined ? myapp.days [ parseInt(response ['DELIVERY_DAYS']) ] : 'дней';
                        var deliveries = response ['DELIVERY_DAYS'] + ' ' + myapp.deliveries [ parseInt(response ['DELIVERY_DAYS']) ];
                        if (myapp.deliveryPrice != 0) {
                            var deliveryPriceTotal = parseInt(response ['DELIVERY_DAYS']) * myapp.deliveryPrice;
                            price_all += '<p style="font-size:1.675rem">Доставка: ' + deliveryPriceTotal + 'р. (' + deliveries/*parseInt(response ['DELIVERY_DAYS']) + ' '+days+*/ + ')</p>';
                            price_all += '<p>Итого: ' + parseInt ( parseInt ( deliveryPriceTotal ) + parseInt ( response ['PRICE'] ) ) + ' р.</p>';
                        }
                        else {
                            price_all = '<p>Итого: ' + response ['PRICE'] + 'р.</p>';
                        }
						$('.price-all').html ( price_all );
                        
                        //console.log ( price_all );
                        
                        myapp.total_price = (isNaN (parseInt(response ['PRICE'])) ? 0 : parseInt(response ['PRICE'])) 
                            + (isNaN(parseInt(deliveryPriceTotal)) ? 0 : parseInt(deliveryPriceTotal));
                        $('.footnote').text ( response ['DAYS'] + ' дней ' + response ['ENERGY'] + ' ккал, ' + parseInt(response ['DAYS']) * 5 + ' порций' );
                        //$('.footnote').text ( response ['DELIVERY_DAYS'] + ' ' + myapp.deliveries [ parseInt(response ['DELIVERY_DAYS']) ] + ' ' + response ['ENERGY'] + ' ккал, ' + parseInt(response ['DAYS']) * 5 + ' порций' );
                        //console.log (response);
                        //console.log (myapp.total_price);
					},
					error: function(data) {
						console.error(data);
					}
				});
			}
		},
        order_when_will_feed_properly: {
            init: function () {
                $(document).on ('click', '[data-name=date]', function (ev) {
                    // выключенное не трогаем
                    if ($(this).hasClass('disable')) return false;
                    $('[data-name=date]').removeClass ('active');
                    $(this).toggleClass ('active');
                    $.myapp.order_when_will_feed_properly.rearrangedays(ev.target);
                    $.myapp.order_during.refresh ();
                });
				$('[data-name=date].active').trigger('click');
            },
            rearrangedays: function(target) {
                var duration; 
                $('[data-name=duration]').each(function(idx, elt){ if($(elt).hasClass('active')) duration = $(elt).data('value');});
                var container_line_1 = $('[data-entity=days_list_line_1]');
                var container_line_2 = $('[data-entity=days_list_line_2]');
                var days = {1: 'пн.', 2: 'вт.', 3: 'ср.', 4: 'чт.', 5: 'пт.', 6: 'сб.', 0: 'вс.'};
                var days_remap = {0: 7, 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6};
                var tpl = '<div data-name="day" data-value="__NUMBER__" data-role="day" class="btn js-btn-order_cb __ACTIVE__">__NAME__</div>';
                var date = new Date();
                var y = $(target).data('year');
                var m = $(target).data('month');
                var d = $(target).data('day');
                date.setFullYear(y);
                //коррекция - месяцы с нуля
                date.setMonth(parseInt(m) - 1);
                date.setDate(d);
                var day = date.getDay();
                var total = duration == '2week' ? 14 : 7;
                var content_line_1 = '';
                var content_line_2 = '';
                var active_counter = duration == '2week' ? 10 : duration == '3days' ? 3 : duration == 'day' ? 1 : 7;
                for(var i=0; i<total; i++) {
                    var position = (day + i) % 7;
                    var item = tpl;
                    item = item.replace('__NUMBER__', days_remap[position]);
                    item = item.replace('__NAME__', days[position]);
                    if(duration == 'week' || duration == '2week'){
                        if(days_remap[position] != 6 && days_remap[position] != 7)
                            if(--active_counter >= 0)
                                item = item.replace('__ACTIVE__', 'active');
                    }
                    else if(duration == '3days' || duration == 'day'){
                        // 3 дня или 1 день
                        if(--active_counter >= 0)
                            item = item.replace('__ACTIVE__', 'active');
                    }
                    if(i<7)
                        content_line_1 += item;
                    else
                        content_line_2 += item;
                }
                $(container_line_1).html( content_line_1 );
                $(container_line_2).html( content_line_2 );
            },
        },
		mymap: {
			init: function () {
				this.map = null, this.lat = 59.949570, this.lng = 30.317649, this.zoom = 13, this.idmap = 'orderMap';
				if (typeof google.maps != 'undefined') {
					this.process ();
                    //this.show_hide_kml_layer ();
				}
			},
            show_hide_kml_layer: function () {
                $(document).on ( 'click', '.js-order-show-area', function (evt) {
                    evt.preventDefault ();
                    var isshow = $(this).hasClass ( 'show' );
                    if (isshow) {
                        $(this).removeClass ( 'show' );
                        $(this).text ( 'Показать районы бесплатной доставки' );
                        Foreach ( myapp.mymap.kmlLayer, function ( k, v ) {
                            v.setMap ( myapp.mymap.map );
                        });
                    }
                    else {
                        $(this).addClass ( 'show' );
                        $(this).text ( 'Показать все зоны доставки' );
                        Foreach ( myapp.mymap.kmlLayer, function ( k, v ) {
                            v.setMap ( $.kml.delivery_area [k] == 0 ? myapp.mymap.map : null );
                        });
                    }
                });
            },
            presetmap: function () {
                this.map.setZoom (this.zoom);
                this.map.setCenter ({lat: this.lat, lng: this.lng});
            },
			placeMarkerAndPanTo: function (latLng, map, marker) {
                document.getElementsByName("address[latlng]")[0].value = JSON.stringify( {'lat':latLng.lat (), 'lng':latLng.lng ()} );
				marker.setPosition(latLng);
				marker.setVisible(true);
				map.panTo(latLng);
				myapp.mymap.geocoder.geocode({'location': latLng}, function(results, status) {
					if (status === 'OK') {
						if (results [0]) {
							document.getElementById("autocomplete").value = results [0].formatted_address;
							for (var i = 0; i < results [0].address_components.length; i++) {
								var addressType = results [0].address_components[i].types[0];
								if (componentForm[addressType]) {
									var val = results [0].address_components[i][componentForm[addressType]];
									document.getElementById(addressType).value = val;
								}
							}
							/// показываем расширенный адрес
							$('.order-field_address').show ("slow");
						} else {
							$.notify ('Сбой геокодера', "error");
						}
                        if (results [1]) {
                            document.getElementsByName("address[sub-locality]")[0].value = results [1].formatted_address;
                        }
					} else {
						$.notify ('Сбой геокодера, статус ответа: ' + status, "error");
					}
                    /**
                     * генерация на изменение адреса в форме заказа
                     */
                    var event = new CustomEvent("addressWritten", { "detail": "" });
                    document.dispatchEvent(event);
				});
			},
			process: function () {
				this.map = new google.maps.Map(document.getElementById( this.idmap ), {
					center: {lat: this.lat, lng: this.lng},
					zoom: this.zoom
				});

                this.geocoder = new google.maps.Geocoder();
				this.input_autocomplete = document.getElementById ('autocomplete');
				this.autocomplete = new google.maps.places.Autocomplete (this.input_autocomplete, {types: ['geocode']});
				this.autocomplete.bindTo('bounds', this.map);
				this.infowindow = new google.maps.InfoWindow();
				this.marker = new google.maps.Marker({
					map: this.map,
					anchorPoint: new google.maps.Point(0, -29),
					icon: '/img/map-marker_m.png'
				});
				this.autocomplete.addListener('place_changed', function() {
					myapp.mymap.infowindow.close();
					myapp.mymap.marker.setVisible(false);
					myapp.mymap.place = myapp.mymap.autocomplete.getPlace();
					if (!myapp.mymap.place.geometry) {
						$.notify ("Autocomplete's returned place contains no geometry", "error");
						return;
					}
					// If the place has a geometry, then present it on a map.
					if (myapp.mymap.place.geometry.viewport) {
						myapp.mymap.map.fitBounds(myapp.mymap.place.geometry.viewport);
					} else {
						myapp.mymap.map.setCenter(myapp.mymap.place.geometry.location);
						myapp.mymap.map.setZoom(17);  // Why 17? Because it looks good.
					}
					myapp.mymap.marker.setPosition(myapp.mymap.place.geometry.location);
					myapp.mymap.marker.setVisible(true);
					myapp.mymap.address = '';
					if (myapp.mymap.place.address_components) {
						myapp.mymap.address = [
							(myapp.mymap.place.address_components[0] && myapp.mymap.place.address_components[0].short_name || ''),
							(myapp.mymap.place.address_components[1] && myapp.mymap.place.address_components[1].short_name || ''),
							(myapp.mymap.place.address_components[2] && myapp.mymap.place.address_components[2].short_name || '')
						].join(' ');
					}
					myapp.mymap.infowindow.setContent('<div><strong>' + myapp.mymap.place.name + '</strong><br>' + myapp.mymap.address);
					myapp.mymap.infowindow.open(myapp.mymap.map, myapp.mymap.marker);
					for (var i = 0; i < myapp.mymap.place.address_components.length; i++) {
						var addressType = myapp.mymap.place.address_components[i].types[0];
						if (componentForm[addressType]) {
							var val = myapp.mymap.place.address_components[i][componentForm[addressType]];
							document.getElementById(addressType).value = val;
						}
					}
					/// показываем расширенный адрес
					$('.order-field_address').show ("slow");
				});
				this.map.addListener('click', function(e, additional) {
                    if ( typeof additional == 'undefined' )
                        return;
                    myapp.deliveryPrice = (typeof additional == 'undefined') ? 0 : additional;
					myapp.mymap.placeMarkerAndPanTo(e.latLng, myapp.mymap.map, myapp.mymap.marker);
                    myapp.order_during.refresh ();
				});
				$('#orderMap').resize(function(){
					google.maps.event.trigger(myapp.mymap.map, "resize");
				});
                $(document)
                .on ('click', '.js-order-show-map', function (ev) {
                    $('#orderMap').appendTo ( $('#orderMapMob') );
                });
			},
            loadKmlLayer: function (src, map) {
                myapp.mymap.kmlLayer [src] = new google.maps.KmlLayer(src, {
                    suppressInfoWindows: true,
                    map: map
                });
                myapp.mymap.kmlLayer [src].addListener('click', function(kmlEvent) {
                    var deliveryPrice = $.kml.delivery_area [this.C];
                    //console.log ($.kml.delivery_area);
                    google.maps.event.trigger(map, 'click', kmlEvent, deliveryPrice);
                });
                //console.log ($.myapp.mymap.kmlLayer);
            },

		},
		form: {
			validateEmail: function (email) {
				var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(email.trim());
			},
            validatePhone: function (phone) {
                var re = /^((8|\+7)-?)?\(?\d{3}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}$/;
                return re.test(phone.trim());
			},
			clear_fields: function (in_form) {
				var i, el, type;
				for (i=0; i<$(in_form).find('input').length; i++) {
					el = $(in_form).find('input') [i];
					type = $(el).attr ('type');
					if ( (type != 'submit') && (type != 'hidden') )
						$(el).val ('');
				}
			},
			check_form: function (in_form) {
				return this.iterate_fields (in_form);
			},
			iterate_fields: function (in_form) {
				var i, el;
				for (i=0; i<$(in_form).find ('input').length; i++) {
					el = $(in_form).find ('input') [i];
					if (this.check_field (el) == 'error')
						return 'error';
				}
				return 'good';
			},
			check_field: function (field) {
				if (this.check_required (field, true) == 'error')
					return 'error';
				if (this.check_required (field, 'email') == 'error')
					return 'error';
				if (this.check_required (field, 'phone') == 'error')
					return 'error';
                if (this.check_required (field, 'email-or-phone') == 'error')
					return 'error';
				return 'good';
			},
			check_required: function (field, compare) {
				var value, form, result;
				form = $(field).closest ('form');
				if (typeof $(field).data ('required') != 'undefined') {
					console.log ($(field).data ('required'));
					if ($(field).data ('required') == compare) {
						value = $(field).val ();
						switch (compare) {
							case true:
								result = (value == '');
								break;
							case 'email':
								result = ! this.validateEmail (value);
								break;
							case 'phone':
								result = (value == '');
								break;
                            case 'email-or-phone':
                                console.log (this.validateEmail (value));
                                console.log (this.validatePhone (value));
                                result = ! (this.validateEmail (value) || this.validatePhone (value));
                                break;
							default:
								result = false;
								break;
						}
						if (result) {
							if (! $(field).parent ().parent ().hasClass ('error') )
								$(field).parent ().parent ().addClass ('error');
                            switch (compare) {
								case true:
									//$.notify ('Поле должно быть заполнено!', 'warn');
									break;
								case 'email':
									//$.notify ('Поле должно содержать корректный email!', 'warn');
									break;
								case 'phone':
									//$.notify ('Поле "телефон" должно быть заполнено!', 'warn');
									break;
                                case 'email-or-phone':
									//$.notify ('Поле должно содержать корректный телефон или E-Mail!', 'warn');
									break;    
								default:
									//$.notify ('Поле должно быть заполнено!', 'warn');
									break;
							}
							return 'error';
						}
					}
				}
				$(field).parent ().parent ().removeClass ('error');
				return 'good';
			}
		},
		order_service: {
			/*
            refresh_token: function () {
				$.get( "/bitrix", {refresh : "1"}, function( response ) {
					console.log (response);
					if (response.STATUS == "N") {
						$.notify (response.message, "error");
					}
				}, "json");
			},
			refresh_products_list: function () {
				$.get( "/index.php?id=21", {}, function( response ) {
					console.log ('Список товаров обновлен - ' + response);
					if (response.STATUS == "N") {
						$.notify (response.message, "error");
					}
				}, "json");
			},
            */
            refresh_token: function() {
                $.ajax({
                    type: 'get',
                    url: '/bitrix',
                    data: {refresh : '1'},
                    dataType: "json",
                    complete: function(res) {
                        var data = res.responseJSON;
                        if ( res.status ){
                            var status = res.status;
                            if ( status == 200 ){
                                if (typeof data != 'undefined' && typeof data.STATUS != 'undefined' && data.STATUS == "N") {
                                    $.notify (data.message, "error");
                                }
                                else{
                                    console.log('Токен успешно обновлен!');
                                }
                            }
                            else{
                                console.log ('Во время запроса произошла непредвиденная ошибка');
                                console.log(res);
                            }
                        }
                        else{
                            console.log ('Во время запроса произошла непредвиденная ошибка');
                            console.log(res);
                        }
                    }
				});
            },
            refresh_products_list: function() {
                $.ajax({
                    type: 'get',
                    url: '/index.php?id=21',
                    data: '',
                    dataType: "json",
                    complete: function(res) {
                        var data = res.responseJSON;
                        if ( res.status ){
                            var status = res.status;
                            if ( status == 200 ){
                                if (typeof data != 'undefined' && typeof data.STATUS != 'undefined' && data.STATUS == "N") {
                                    $.notify (data.message, "error");
                                }
                                else{
                                    console.log('Данные успешно обновлены!');
                                }
                            }
                            else{
                                console.log ('Во время запроса произошла непредвиденная ошибка');
                                console.log(res);
                            }
                        }
                        else{
                            console.log ('Во время запроса произошла непредвиденная ошибка');
                            console.log(res);
                        }
                    }
				});
            },
            prevent_double_order: function () {
                if (myapp.order_service.can_do_order == true) {
                    myapp.order_service.can_do_order = false;
                    return true;
                }
                else {
                    $.notify ( 'Пожалуйста подождите...', 'info' );
                    return false;
                }
            },
            getformdata: function () {
                var $form = $('#popupOrder > form');
                var data = $form.serialize();
                $form.find('[data-name]').each(function(){
                    var $t = $(this);
					if ( $t.hasClass('active') ) {
						data += '&' + $t.data('name') + '=' + $t.data('value');
					}
				});
				/// Удобное время доставки
				var from_time = $('.noUi-handle[data-handle=0]').first ().text ();
				var to_time = $('.noUi-handle[data-handle=1]').first ().text ();
				data += '&from_time='+from_time+'&to_time='+to_time;
				/// Цена
				var price = $($('.price-all') [0]).text ();//parseInt($($('.price-all') [0]).text ());
                price = /\d+/.exec (price);
                //console.log (reg.exec(price));
                console.log (price [0]);
				data += '&price='+price [0];
                /// Район
                var sub_locality = document.getElementsByName("address[sub-locality]")[0].value;
				data += '&address[sub-locality]='+sub_locality;
                data += '&total='+myapp.total_price; 
                return data;
            },
			init: function () {
                this.can_do_order = true;
                $(document).on ( 'focusout', '#popupOrder > form input', function(e) {
                    var $form = $('#popupOrder > form');
                    myapp.form.check_form ($form);
                });
                /*
                $(document).on('keyup', '#popupOrder > form input', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                        var inputs = $(this).closest('form').find(':input');
                        inputs.eq( inputs.index(this)+ 1 ).focus();
                        return false;
                    }
                });
                $(document).on('keypress', '#popupOrder > form input', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                */ 
				$(document).on('submit', '#popupOrder > form', function(e) {
					e.preventDefault();
                    
                    var $form = $(this);
                    
                    if ( !myapp.order_service.prevent_double_order () )
                        return false;

					if ( myapp.form.check_form ($form) == "good" ) {
                        /// форму на сабмит, включаем колесо
                        myapp.modals.waiting ();
                        var data = $.myapp.order_service.getformdata ();
                        var days = $('[data-role=day]').siblings ('.active').length;
						$.ajax({
							type: $form.attr('method'),
							url: $form.attr('action'),
							data: data + '&days='+days,
							dataType: "json",
							complete: function(res) {
								
                                myapp.modals.stop_waiting ();
                                try {
                                    var data = res.responseJSON;
                                    if ( res.status ){
                                        var status = res.status;
                                        if ( status == 200 ){	/** success */
                                            if (typeof data.error != 'undefined') {
                                                $.notify ( data.error_description, "error" );
                                            }
                                            else {
                                                if (data.discount == true) {
                                                    myapp.modals.open ('#popupShare');
                                                }
                                                else {
                                                    var yandex = '<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/quickpay/shop-widget?account=410014447291630&quickpay=shop&payment-type-choice=on&mobile-payment-type-choice=on&writer=seller&targets-hint=&default-sum=&button-text=01&successURL=http%3A%2F%2Frealfood.pro%2F&targets=__TARGETS__&default-sum=__DEFAULT_SUM__" width="450" height="198"></iframe>';
                                                    var targets = 'Заказ № '+ data.order;
                                                    var total = myapp.total_price;
                                                    yandex = yandex.replace ( '__TARGETS__', targets );
                                                    yandex = yandex.replace ( '__DEFAULT_SUM__', total );
                                                    myapp.popup.info ( yandex );
                                                    //app.module.popup.info('Спасибо, Ваша заказ № '+ data.order +' успешно принят.', 'Оплату заказа на сумму '+myapp.total_price+' рублей можно произвести по ссылке https://money.yandex.ru/direct-payment.xml?form-state=to-card. Обязательно укажите в коментариях номер Вашего заказа.' );
                                                }
                                            }
                                        } else {	/** other trouble */
                                            $.notify ( 'При отправке сообщения произошла ошибка. Обратитесь к администратору! ('+res+')', "error" );
                                            alert ( 'Извините, сервер временно недоступен! Повторите Ваш запрос заново. Спасибо.' );
                                        }
                                    }
                                    else {	/** other trouble */
                                        $.notify ( 'При отправке сообщения произошла ошибка. Обратитесь к администратору! ('+res+')', "error" );
                                        alert ( 'Извините, сервер временно недоступен! Повторите Ваш запрос заново. Спасибо.' );
                                    }
                                }
                                catch (err) {
                                    $.notify ( err.message, "error" );
                                    myapp.order_service.can_do_order = true;
                                }
                                /// можно заказывать снова
                                myapp.order_service.can_do_order = true;
							}
						});
					}
                    else {
                        /// если форма с косяками, то разрешаем заказывать вновь
                        myapp.order_service.can_do_order = true;
                    }
				})
				.on ('click', '[href=#popupOrder]', function (ev) {
					var calory_result = parseInt($('.js-calculate-result').text ());
					var result = {};
					$('[data-name=energy]').siblings ().each (function(index, element) {
						var value = $(element).data('value');
						result [value] = isNaN ( Math.abs (value - calory_result) ) ? 0 : Math.abs (value - calory_result);
					});
					var min = 1000000000;
					var calories;
					Foreach (result, function (k, v) {
						if (v < min) {
							min = v;
							calories = parseInt(k);
						};
					});
					$('[data-name=energy]').siblings ().each (function(index, element) {
						var value = $(element).data('value');
						if (value == calories)
							$(element).addClass ('active');
						else
							$(element).removeClass ('active');
					});
					myapp.order_during.refresh ();
                    myapp.mymap.presetmap ();
				});
				
				$(document).on ('submit', '.form', function (ev) {
					ev.preventDefault ();
					var url = $(this).attr ('action'), self = this;
					if ( myapp.form.check_form (this) == 'good' ) {
                        myapp.modals.waiting ();
						$.ajax({
							url: url,
							method: "POST",
							data: $(this).serialize (),
							dataType: "json"
						}).done(function(response) {
                            myapp.modals.stop_waiting ();
							if (response.result == 'ERROR') {
								$.notify (response.data, 'error');
                            }
							else {
                                myapp.modals.info ('Мы обязательно свяжемся с Вами', response.data);
                                myapp.form.clear_fields (self);
							}
						}).fail(function(response){
							myapp.modals.stop_waiting ();
							$.notify (response.message, 'error');
						});
					}
				});
			}
		},
        modals: {
            close: function () {
                app.module.popup.close('.popup');
                myapp.callbacks.launch ()
            },
            open: function (target) {
                app.module.popup.open (target);
            },
            info: function(title, text, callback) {
                app.module.popup.info (title, text, callback);
            },
            waiting: function () {
                this.close ();
                this.set_loader ();
            },
            stop_waiting: function () {
                this.unset_loader ();
            },
            set_loader: function () {
                $( "body" ).append( '<div class="ajax_loader"></div><div class="popup-overlay"></div>' );
            },
            unset_loader: function () {
                var ajax_loader = $('.ajax_loader');
                var popup_overlay = $('.ajax_loader').next();
                $(ajax_loader).remove();
                $(popup_overlay).remove();
            },
            result: {
                init: function () {
                    this.pressOK ();
                },
                pressOK: function () {
                    $(document).on ('click', '.popup_close', $.proxy (function (ev) {
                        ev.preventDefault ();
                        this.close ();
                    }, myapp.modals));
                },
            },
        },
        popup: {
				create: function(popup){
					var pref = popup.indexOf('#') == 0  ? 'id' : 'class';
					var name = popup.replace(/^[\.#]/,'');
					var $popup = $('<div class="popup">'
						+			'<div class="popup-inner">'
						+				'<div class="popup-layout">'
						+					'<div class="popup-content"></div>'
						+				'</div>'
						+			'</div>'
						+			'<div class="popup-overlay"></div>'
						+		'</div>').appendTo('body');

					if ( pref == 'id'){
						$popup.attr(pref, name);
					} else {
						$popup.addClass(name);
					}

					return $popup;
				},
                open: function(popup, html){
					setTimeout(function(){
						try{$.fn.fullpage.setAllowScrolling(false);} catch (e){}
					}, 10);
					myapp.popup.close('.popup');
					var $popup = $(popup);
					if (!$popup.size()){
						$popup = myapp.popup.create(popup);
					}
					if( html ){
						$popup.find('.popup-content').html(html);
					}
					$('body').addClass('overflow_hidden');
					return $popup.show();
				},
				close: function(popup){
					try{$.fn.fullpage.setAllowScrolling(true);} catch (e){}
					var $popup = $(popup);
					$('body').removeClass('overflow_hidden');
					$popup.hide();
				},
                info: function(title, text, callback){
					var html = '<div class="popup-title">' + (title ? title : '') + '</div>'
						+	'<div class="popup-text">' + (text ? text : '') + '</div>';
					myapp.popup.open('.popup_info', html);

					if ( callback ) {
						$('.popup_info').find('.btn').click(callback);
					} else {
						$('.popup_info').find('.btn').click(function(){
							myapp.popup.close($('.popup_info'));
						});
					}
				},
        },
        buttons: {
            init: function () {
                $(document)
					.on('click', '.js-load-day-menu', function(e) {
						e.preventDefault();
						var $t = $(this),
							$wrap = $t.closest('.tabs-target');

						$t.addClass( 'active' ).siblings ().removeClass ( 'active' );
						$wrap.find( '.example-set__item' ).removeClass ( 'active' );
						$wrap.find( '.example-set__item[data-storage="'+$(this).data ('storage')+'"]' ).addClass ( 'active' );
					});
            },
        },
	};
	var loader = function(){
        jQuery.myapp = myapp;
		myapp.init();
	};
	var ali = setInterval(function(){
		if (typeof jQuery !== 'function') return;
		clearInterval(ali);
		setTimeout(loader, 0);
	}, 50);

})();



