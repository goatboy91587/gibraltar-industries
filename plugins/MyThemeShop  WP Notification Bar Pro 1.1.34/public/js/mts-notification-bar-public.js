(function( $ ) {

	'use strict';

	$(function() {

		var barHeight, mtsnbSlider = false, mtsnbSliderContainer, stageOuterHeight, newStageOuterHeight;

		// Show notification bar
		if ( $('.mtsnb').length > 0 ) {
			barHeight = $('.mtsnb').outerHeight();
			var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
			if ( $('.mtsnb').hasClass('mtsnb-shown') && ! $('.mtsnb').hasClass('mtsnb-slidein-posts') ) {
				$('body').css( cssProperty, barHeight ).addClass('has-mtsnb-shown');
				$( document ).trigger( 'mtsnbShown', [ barHeight, cssProperty, $('.mtsnb').attr('data-mtsnb-id') ] );
			} else {
				$('body').addClass('has-mtsnb-closed');
			}
			$('body').addClass('has-mtsnb');

			var mtsnbAnimation        = $('.mtsnb').attr('data-bar-animation');
			var mtsnbContentAnimation = $('.mtsnb').attr('data-bar-content-animation');
		
			if ( '' !== mtsnbAnimation ) {

				$('.mtsnb').removeClass('mtsnb-invisible').addClass( 'mtsnb-animated '+mtsnbAnimation );
			}
			if ( '' !== mtsnbContentAnimation ) {
				$('.mtsnb-content').addClass('mtsnb-content-hidden');
			}
			if ( '' !== mtsnbAnimation ) {
				$('.mtsnb').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
					$('.mtsnb').removeClass( 'mtsnb-animated '+mtsnbAnimation );
					if ( '' !== mtsnbContentAnimation ) {
						$('.mtsnb-content').removeClass('mtsnb-content-hidden').addClass( 'mtsnb-animated '+mtsnbContentAnimation );
					}
				});
			} else {
				if ( '' !== mtsnbContentAnimation ) {
					$('.mtsnb-content').removeClass('mtsnb-content-hidden').addClass( 'mtsnb-animated '+mtsnbContentAnimation );
				}
			}

			$(window).on('orientationchange resize load', function() {
				var newBarHeight = $('.mtsnb').outerHeight();
				if ( $('.mtsnb').hasClass('mtsnb-shown') && ! $('.mtsnb').hasClass('mtsnb-slidein-posts') && barHeight !== newBarHeight ) {
					barHeight = newBarHeight;
					$('body').css( cssProperty, barHeight );
					$( document ).trigger( 'mtsnbHeightChanged', [ barHeight, cssProperty, $('.mtsnb').attr('data-mtsnb-id') ] );
				}
			});
		}

		// Slider
		if ( $('.mtsnb-slider').length > 0 ) {

			mtsnbSlider = $('.mtsnb-slider');
			mtsnbSliderContainer = mtsnbSlider.closest('.mtsnb-slider-container');
				
			mtsnbSlider.owlCarousel({
				items: 1,
				loop: true,
				nav: false,
				dots: false,
				onInitialized: function(){
					mtsnbSliderContainer.removeClass('loading');
					stageOuterHeight = parseInt( $('.owl-height').css('height'), 10 );
				},
				onChange: function(){
					stageOuterHeight = parseInt( $('.owl-height').css('height'), 10 );
				},
				autoplay: true,
				autoHeight: true,
				margin: 10,
			});

			mtsnbSlider.on('changed.owl.carousel', function(event) {
				var currentIndex = event.item.index;
				var newStageOuterHeight = mtsnbSlider.find('.owl-stage').children().eq( currentIndex ).height();
				var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
				if ( $('.mtsnb').hasClass('mtsnb-shown') ) {
					barHeight = parseInt( $('body').css(cssProperty) ) - stageOuterHeight + newStageOuterHeight;
					$('body').css( cssProperty, barHeight );
					$( document ).trigger( 'mtsnbHeightChanged', [ barHeight, cssProperty, $('.mtsnb').attr('data-mtsnb-id') ] );
				} else {
					$('body').css( cssProperty, '0' );
				}
			});
		}

		// Hide Button
		$(document).on('click', '.mtsnb-hide', function(e) {

			e.preventDefault();

			var $this = $(this);
			var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
			var bar_id = $('.mtsnb').attr('data-mtsnb-id');

			if ( !$this.hasClass('active') ) {
				$this.closest('.mtsnb').removeClass('mtsnb-shown').addClass('mtsnb-hidden');
				$('body').css( cssProperty, 0 ).removeClass('has-mtsnb-open').addClass('has-mtsnb-closed');;
				$( document ).trigger( 'mtsnbHidden', [ 0, cssProperty, bar_id ] );
			}

			if ( mtsnbSlider ) {
				mtsnbSlider.trigger('stop.owl.autoplay');
			}
			
			if ( $('.mtsnb').hasClass('mtsnb-remember-state') ) {

				$.cookie('mtsnb_state_'+bar_id, 'closed', { path: '/' });

			} else {

				$.cookie('mtsnb_state_'+bar_id, '', { path: '/' });
			}
		});

		// Show Button
		$(document).on('click', '.mtsnb-show', function(e) {

			e.preventDefault();

			var $this = $(this);
			var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
			var bar_id = $('.mtsnb').attr('data-mtsnb-id');

			if ( !$this.hasClass('active') ) {
				barHeight = $('.mtsnb').outerHeight();
				$this.closest('.mtsnb').removeClass('mtsnb-hidden').addClass('mtsnb-shown');
				$('body').css( cssProperty, barHeight ).addClass('has-mtsnb-open').removeClass('has-mtsnb-closed');
				if ( $('.mtsnb').hasClass('mtsnb-bottom') && ( $(window).scrollTop() + $(window).height() == $(document).height() ) )  {
					$("html, body").animate({ scrollTop: $(window).scrollTop()+barHeight }, 300);
				}
				$( document ).trigger( 'mtsnbShown', [ barHeight, cssProperty, bar_id ] );
			}

			if ( mtsnbSlider ) {
				setTimeout(function (){
					mtsnbSlider.trigger( 'play.owl.autoplay', [5000] );
				}, 5000);
			}

			if ( $('.mtsnb').hasClass('mtsnb-remember-state') ) {

				$.cookie('mtsnb_state_'+bar_id, 'opened', { path: '/' });

			} else {

				$.cookie('mtsnb_state_'+bar_id, '', { path: '/' });
			}
		});

		if($(document).find('.mtsnb-slidein-posts').length > 0) {
			var new_items = $('.mtsnb-slidein-posts .mtsnb-sp-container .mtsnb-post[data-new]').length;
			var menu_selector = $(document).find('.mtsnb-slidein-posts').data('sp-selector');
			if(menu_selector) {
				if(new_items > 0) {
					$('#'+menu_selector).append('<span class="mtsnb-sp-icon active">'+new_items+'</span>');
				}
				$(document).on('click', '#'+menu_selector, function(e){
					e.preventDefault();
					$('.mtsnb.mtsnb-slidein-posts').toggleClass('in');
					$('.mtsnb-sp-mask').toggleClass('in');
					return false;
				});
			}
			if(new_items > 0) {
				$(document).find('#mtsnb-sp-selector').find('.mtsnb-sp-icon').addClass('active').text(new_items);
			}

			$(document).on('click', '#mtsnb-sp-selector', function(e){
				e.preventDefault();
				$('.mtsnb.mtsnb-slidein-posts').toggleClass('in');
				$('.mtsnb-sp-mask').toggleClass('in');
				return false;
			});

			$(document).on('click', '.mtsnb-sp-mask.in', function(e){
				e.preventDefault();
				$('.mtsnb.mtsnb-slidein-posts').removeClass('in');
				$('.mtsnb-sp-mask').removeClass('in');
				return false;
			});

			$(document).on('click', '.mtsnb.mtsnb-slidein-posts .mtsnb-close-sp', function(e){
				e.preventDefault();
				$('.mtsnb.mtsnb-slidein-posts').removeClass('in');
				$('.mtsnb-sp-mask').removeClass('in');
				return false;
			});
		}

		// Cookie - how many times user has seen specific bar
		if ( $('.mtsnb').length > 0 ) {

			$('.mtsnb').each(function() {
				var bar_id = $(this).attr('data-mtsnb-id');
				var mtsnbSeen = $.cookie('mtsnb_seen_'+bar_id);

				if ( !mtsnbSeen ) {

					$.cookie('mtsnb_seen_'+bar_id, '1', { expires: parseInt(mtsnb_data.cookies_expiry), path: '/' });

				} else {

					mtsnbSeen = parseInt( mtsnbSeen );
					$.cookie('mtsnb_seen_'+bar_id, ++mtsnbSeen, { expires: parseInt(mtsnb_data.cookies_expiry), path: '/' });
				}

				// Record Impression
				var ab_variation = $(this).find('.mtsnb-content').attr('data-mtsnb-variation');
				$.post( mtsnb_data.ajaxurl, {
					action: 'mtsnb_add_impression',
					bar_id: bar_id,
					ab_variation: ab_variation
				});
			});
		}

		// Cookie - show bar after x visits
		if ( $('.mtsnb-delayed').length > 0 ) {

			$('.mtsnb-delayed').each(function() {
				var bar_id = $(this).attr('data-mtsnb-id');
				var number = $(this).attr('data-mtsnb-after');
				var emtsnb  = $.cookie('mtsnb_'+bar_id+'_after');
					
				if ( !emtsnb ) {

					$.cookie('mtsnb_'+bar_id+'_after', number-1, { expires: parseInt(mtsnb_data.cookies_expiry), path: '/' });

				} else {

					emtsnb = parseInt( emtsnb );
					if ( 0 < emtsnb ) {
						$.cookie('mtsnb_'+bar_id+'_after', --emtsnb, { expires: parseInt(mtsnb_data.cookies_expiry), path: '/' });
					}
				}
			});
		}

		// Record Click
		$(document).on('click', '.mtsnb-container', function(event) {

			// Link or submit
			if ( $(event.target).closest('a').length || $(event.target).hasClass('mtsnb-submit') ) {

				var bar_id = $(event.target).closest('.mtsnb').attr('data-mtsnb-id'),
					ab_variation = $(event.target).closest('.mtsnb-content').attr('data-mtsnb-variation');

				$.post( mtsnb_data.ajaxurl, {
					action: 'mtsnb_add_click',
					bar_id: bar_id,
					ab_variation: ab_variation
				});
			}
		});

		// Video popup
		if ( $('.mtsnb-popup-type').length > 0 ) {

			$('.mtsnb-popup-youtube, .mtsnb-popup-vimeo').magnificPopup({
				disableOn: 700,
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false
			});
		}
		// Email Signup Form
		if ( $('#mtsnb-newsletter-type').length > 0 ) {

			$('#mtsnb-newsletter').submit(function(event){
				if ($('#mtsnb-newsletter-type').html() == 'aweber' ||
					$('#mtsnb-newsletter-type').html() == 'MailChimp' ||
					$('#mtsnb-newsletter-type').html() == 'getresponse' ||
					$('#mtsnb-newsletter-type').html() == 'campaignmonitor' ||
					$('#mtsnb-newsletter-type').html() == 'active_campaign' ||
					$('#mtsnb-newsletter-type').html() == 'constant_contact' ||
					$('#mtsnb-newsletter-type').html() == 'madmimi' ||
					$('#mtsnb-newsletter-type').html() == 'benchmark' ||
					$('#mtsnb-newsletter-type').html() == 'sendinblue' ||
					$('#mtsnb-newsletter-type').html() == 'convertkit' ||
					$('#mtsnb-newsletter-type').html() == 'drip') {
					event.preventDefault();
					$('<i style="margin-left: 10px;" class="mtsnb-submit-spinner fa fa-spinner fa-spin"></i>').insertAfter('.mtsnb-submit');

					var data = {
						'action': 'mtsnb_add_email',
						'bar_id': $('.mtsnb').attr('data-mtsnb-id'),
						'type': $('#mtsnb-newsletter-type').html(),
						'email': $('#mtsnb-email').val(),
						'first_name': $('#mtsnb-first-name').val(),
						'last_name': $('#mtsnb-last-name').val(),
						'ab_variation': $(this).closest('.mtsnb-content').attr('data-mtsnb-variation'),
					};
					if($('#mtsnb-consent-field').length > 0) {
						data.consent = $('#mtsnb-consent-field:checked').length ? $('#mtsnb-consent-field:checked').val() : 'no';
					}
					$.post(mtsnb_data.ajaxurl, data, function(response) {
						response = $.parseJSON(response);
						$('.mtsnb-submit-spinner').remove();
						$('.mtsnb-message').html('<i class="fa fa-' + response.status + '"></i> ' + response.message);
						$('.mtsnb-message').css('margin-top', '10px');
						var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
						var mtsnbEventParams = [ barHeight, cssProperty, data.bar_id ];
						if ( 'check' === response.status ) {
							$( document ).trigger( 'mtsnbSubscribed', mtsnbEventParams );
							if($('#mtsnb-consent-field').length > 0) {
								$('#mtsnb-consent-field').prop('checked', false);
							}
						}
						$( document ).trigger( 'mtsnbHeightChanged', mtsnbEventParams );
					});

				}
			});
		}

		// Counter
		if ( $('.mtsnb-countdown-type, .mtsnb-countdown-b-type').length > 0 ) {

			var coutTill = $('.mtsnb-clock-till').val();
			var clock = $('.mtsnb-clock').FlipClock( coutTill, {
				clockFace: 'DailyCounter',
				countdown: true
			});
		}
	});

})( jQuery );
