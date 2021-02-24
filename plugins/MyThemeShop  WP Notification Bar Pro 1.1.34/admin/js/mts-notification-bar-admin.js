(function( $ ) {
	'use strict';

	$(function() {

		// Tabs
		$(document).on('click', '.mtsnb-tabs-nav a', function(e){
			e.preventDefault();
			var $this = $(this),
				target = $this.attr('href');
			if ( !$this.hasClass('active') ) {
				$this.parent().parent().find('a.active').removeClass('active');
				$this.addClass('active');
				$this.parent().parent().next().children().siblings().removeClass('active');
				$this.parent().parent().next().find( target ).addClass('active');
				$this.parent().parent().prev().val( target.replace('#tab-',''));
			}
		});

		// Display conditions manager
		$(document).on('click', '.condition-checkbox', function(e){
			var $this = $(this),
				panel = '#'+$this.attr('id')+'-panel',
				disable = $this.data('disable');
			if ( !$this.hasClass('disabled') ) {
				if ( $this.hasClass('active') ) {
					$this.removeClass('active');
					$this.find('input').val('');
					$(panel).removeClass('active');
					if ( disable ) {
						$('#condition-'+disable).removeClass('disabled');
					}
				} else {
					$this.addClass('active');
					$(panel).addClass('active');
					$this.find('input').val('active');
					if ( disable ) {
						$('#condition-'+disable).addClass('disabled');
					}
				}
			}
		});

		// Checkbox toggles
		$(document).on('change', '.mtsnb-checkbox-toggle', function(e) {
			var $this = $(this),
				targetSelector = '[data-checkbox="'+$this.attr('id')+'"]';

			$( targetSelector ).toggleClass('active');
		});

		// Preview Bar Button
		$(document).on('click', '#preview-bar', function(e){
			e.preventDefault();
			$('.mtsnb').remove();
			$('body').css({ "padding-top": "0", "padding-bottom": "0" }).removeClass('has-mtsnb');
			var form_data = $('form#post').serialize();
			var data = {
                action: 'preview_bar',
                form_data: form_data,
            };

            $.post( ajaxurl, data, function(response) {
                if ( response ) {
                    $('body').prepend( response );
                }
            }).done(function(result){
            	$( document ).trigger('mtsnbPreviewLoaded');
            	if($('form#post #mtsnb_fields_content_type').val() === 'slidein-posts') {
								$('body').css('padding-top', 0);
								if($(document).find('.mtsnb-slidein-posts').length > 0) {
									var new_items = $('.mtsnb-slidein-posts .mtsnb-sp-container .mtsnb-post[data-new]').length;
									
									$('.mtsnb.mtsnb-slidein-posts').toggleClass('in');
									$('.mtsnb-sp-mask').toggleClass('in');

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
            	}
            });

		});

		$(document).on('click', '.mtsnb-add-block', function(e){
			e.preventDefault();
			var $parent = $(this).parents('.form-row');
			var block_wrapper = $(this).prev().find('.mtsnb-inner-wrapper');
			var i = block_wrapper.length + 1;
			var clonediv = $(block_wrapper).first().clone();
			$(clonediv).find('.mtsnb-section-title span').html('Content '+i);
			$(clonediv).find('input, textarea').val('');
			$(clonediv).find('.image-shown').removeClass('image-shown').find('img').attr('src', '');
			$(this).prev().append(clonediv);
			mtsnb_rearrange_sp_sections($parent);
			return false;
		});

		$(document).on('click', '.mtsnb-remove-cc-section', function(e){
			e.preventDefault();
			var $parent = $(this).parents('.form-row');
			$(this).parents('.mtsnb-inner-wrapper').remove();
			mtsnb_rearrange_sp_sections($parent);
			return false;
		});

		$('.mtsnb-repeater-wrapper').sortable({
			update: function(event, ui) {
				console.log(event.target);
				var $parent = $(event.target).parents('.form-row');
				mtsnb_rearrange_sp_sections($parent);
			}
		});

		function mtsnb_rearrange_sp_sections($parent) {
			var name = 'sp';
			if($parent.parents('#tab-slidein-posts-b').length > 0) {
				name = 'b_sp';
			}
			$parent.find($('.mtsnb-repeater-wrapper .mtsnb-inner-wrapper')).each(function(key, value){
				var i = key+1;
				$(this).find('.mtsnb-section-title span').html('Content '+i);
				$(this).find('.form-row').each(function(){
					$(this).find('input, textarea').each(function(){
						var id = $(this).attr('id');
						$(this).attr('name', 'mtsnb_fields['+name+']['+key+']['+id+']');
					});
				});
			});
		}

		$(document).on('click', '.mtsnb-repeater-wrapper .mtsnb-section-title', function(e){
			e.preventDefault();
				$(this).next('.mtsnb-content-wrapper').slideToggle();
			return false;
		});

		$(document).on('click', '.mtsnb_fields_image_upload', function(e){
		e.preventDefault();

		var button = $(this),
		    custom_uploader = wp.media({
					title: 'Insert image',
					library : {
						// uncomment the next line if you want to attach image to the current post
						// uploadedTo : wp.media.view.settings.post.id, 
						type : 'image'
					},
					button: {
						text: 'Use this image' // button label text
					},
					multiple: false // for multiple image selection set to true
				}).on('select', function() { // it also has "open" and "close" events 
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					$(button).parent().addClass('image-shown').find('img').attr('src', attachment.url);
					$(button).parent().find('#id').val(attachment.id);
					$(button).parent().find('#image').val(attachment.url);
				}).open();
		});

		$(document).on('click', '.mtsnb-clear-image', function(e){
			e.preventDefault();
			$(this).parent().removeClass('image-shown').find('img').attr('src', '');
			$(this).parent().find('#mtsnb_fields_image_id').val('');
			$(this).parent().find('#mtsnb_fields_image').val('');
			return false;
		});

		// Preview Bar Button
		$(document).on('click', '.wpnb-reset-action', function(e){
			e.preventDefault();
			if(confirm("Are you sure you want to delete this?")){
				var $this = $(this),
						org_text = $this.text();

				var data = {
          action: 'remove_impressions',
          id: $this.data('post-id'),
          reset_op : $this.attr('id')
        };
        $this.text('Processing...');
	      $.post( ajaxurl, data, function(response) {
	      	if(response) {
	      		$this.text('Completed!');	
	      	} else {
	      		$this.text('Failed!');
	      	}
					setTimeout( function() {
						$this.text( org_text );
					}, 2000 );
	      });
			} else{
				return false;
			}
		});

		$( document ).on( 'mtsnbPreviewLoaded', function( event ) {

			var barHeight, mtsnbSlider = false, mtsnbSliderContainer, stageOuterHeight, newStageOuterHeight;
			if ( $('.mtsnb').length > 0 ) {
				barHeight = $('.mtsnb').outerHeight();
				var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
				$('body').css(cssProperty, barHeight).addClass('has-mtsnb');

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
					//autoplayTimeout: 5000,
	    			//autoplayHoverPause: true,
					autoHeight: true,
					margin: 10,
				});

				mtsnbSlider.on('changed.owl.carousel', function(event) {
			        var currentIndex = event.item.index;
			        var newStageOuterHeight = mtsnbSlider.find('.owl-stage').children().eq( currentIndex ).height();
			        var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
			        if ( $('.mtsnb').hasClass('mtsnb-shown') ) {
						$('body').css(cssProperty, parseInt( $('body').css(cssProperty) ) - stageOuterHeight + newStageOuterHeight );
			        } else {
			        	$('body').css(cssProperty, '0' );
			        }
			    });
			}

			$(document).on('click', '.mtsnb-hide', function(e) {

				e.preventDefault();

				var $this = $(this);
				var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';

				if ( !$this.hasClass('active') ) {
					$this.closest('.mtsnb').removeClass('mtsnb-shown').addClass('mtsnb-hidden');
					$('body').css(cssProperty, 0);
				}

				if ( mtsnbSlider ) {
					mtsnbSlider.trigger('stop.owl.autoplay');
				}
			});

			// Show Button
			$(document).on('click', '.mtsnb-show', function(e) {

				e.preventDefault();

				var $this = $(this);
				var cssProperty =  $('.mtsnb').hasClass('mtsnb-bottom') ? 'padding-bottom' : 'padding-top';
				if ( !$this.hasClass('active') ) {
					barHeight = $('.mtsnb').outerHeight();
					$this.closest('.mtsnb').removeClass('mtsnb-hidden').addClass('mtsnb-shown');
					$('body').css(cssProperty, barHeight);
					if ( $('.mtsnb').hasClass('mtsnb-bottom') && ( $(window).scrollTop() + $(window).height() == $(document).height() ) )  {
						$("html, body").animate({ scrollTop: $(window).scrollTop()+barHeight }, 300);
					}
				}

				if ( mtsnbSlider ) {
					setTimeout(function (){
						mtsnbSlider.trigger('play.owl.autoplay', [5000] );
					}, 5000);
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

			// Counter
			if ( $('.mtsnb-countdown-type').length > 0 ) {

				var coutTill = $('.mtsnb-clock-till').val();
				var clock = $('.mtsnb-clock').FlipClock( coutTill, {
					clockFace: 'DailyCounter',
					countdown: true
				});
			}
		});

		// Color option
		$('.mtsnb-color-picker').wpColorPicker();

		// Referral/Custom URL options
		$('select.mtsnb-multi-text').tagsinput({
			confirmKeys: [13, 32],
			trimValue: true
		});

		// Multi select options
		$('select.mtsnb-multi-select').select2();

		// Icon select options
		$('select.mtsnb-icon-select').select2({
	        formatResult: function(state) {
	            if (!state.id) return state.text; // optgroup
	            return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
	        },
	        formatSelection: function(state) {
	            if (!state.id) return state.text; // optgroup
	            return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
	        },
	        escapeMarkup: function(m) { return m; }
	    });

		// Select which shows/hides options based on its value
		function mtsnbShowHodeChildOptions( el ) {
	        var $this = $(el),
				tempValue = $this.val(),
				targetSelector = '[data-parent-select-id="'+$this.attr('id')+'"]',
				activeSelector = '[data-parent-select-value*="'+tempValue+'"]';

			$( targetSelector ).removeClass('active');

	        if ( tempValue && activeSelector ) {

	            $( targetSelector+activeSelector ).addClass('active');
	        }
	    }

	    $('.mtsnb-has-child-opt select').each(function() {
            mtsnbShowHodeChildOptions( $(this) );
        });

		$(document).on('change', '.mtsnb-has-child-opt select', function(e) {
			mtsnbShowHodeChildOptions( $(this) );
		});

		// Datepickers
		var mtsnbDateToday = new Date();
		$( '.mtsnb-datepicker' ).datepicker({
	        dateFormat: 'mm/dd/yy',
	        minDate: mtsnbDateToday,
	    });
	    $('.mtsnb-condition-datepicker').datepicker({
	        dateFormat: 'mm/dd/yy',
	    });
	    $('#ui-datepicker-div').wrap('<div class="mts-datepicker"></div>');

		// Timepicker
		$('.mtsnb-timepicker').timepicker();

		// Social icons sorter
		$('#mtsnb-social-div-tbody').sortable();
		$('#mtsnb-b-social-div-tbody').sortable();

		var x = $('#mtsnb-social-div-tbody tr').length;
		var xb = $('#mtsnb-b-social-div-tbody tr').length;

		$('#mtsnb-social-add-platform').click( function(e) {

			var social_type = '<select id="mtsnb_fields_social_type" name="mtsnb_fields[social]['+(x+1)+'][type]">';

			$.each( adminVars.social_icons, function( key, value ) {
				social_type += '<option value="' + key + '">' + value + '</option>';
			});

			social_type += '</select>';

			var social_url = '<input class="form-control" id="mtsnb_fields_social_url" name="mtsnb_fields[social]['+(x+1)+'][url]" type="text" value="" placeholder="http://example.com"/>';

			var remove = '<button class="mtsnb-social-remove-platform button"><i class="fa fa-close"></i> ' + adminVars.remove + '</button>';

			var move = '<i class="mtsnb-move fa fa-arrows"></i>';

			var new_platform = '<tr class="text-center"><td>' + move + '</td><td>' + social_type + '</td><td>' + social_url + '</td><td>' + remove + '</td></tr>';

			$('#mtsnb-social-div-tbody').append( new_platform );

			x++;

			return false;
		});

		$('#mtsnb-b-social-add-platform').click( function(e) {

			var social_type_b = '<select id="mtsnb_fields_b_social_type" name="mtsnb_fields[b_social]['+(xb+1)+'][type]">';

			$.each( adminVars.social_icons, function( key, value ) {
				social_type_b += '<option value="' + key + '">' + value + '</option>';
			});

			social_type_b += '</select>';

			var social_url_b = '<input class="form-control" id="mtsnb_fields_b_social_url" name="mtsnb_fields[b_social]['+(xb+1)+'][url]" type="text" value="" placeholder="http://example.com"/>';

			var remove_b = '<button class="mtsnb-social-remove-platform button"><i class="fa fa-close"></i> ' + adminVars.remove + '</button>';

			var move = '<i class="mtsnb-move fa fa-arrows"></i>';

			var new_platform_b = '<tr class="text-center"><td>' + move + '</td><td>' + social_type_b + '</td><td>' + social_url_b + '</td><td>' + remove_b + '</td></tr>';

			$('#mtsnb-b-social-div-tbody').append( new_platform_b );

			xb++;

			return false;
		});

		$('body').on('click', '.mtsnb-social-remove-platform', function(e){

			$(this).parent('td').parent('tr').remove();

			return false;
		});

		// Add UTM tag
		$(document).on( 'click', '.mtsnb-add-utm-tag', function(e) {

			e.preventDefault();

			var $this = $(this),
				$newItemLabelField = $this.parent().find('.mtsnb-add-utm-tag-input'),
				newItemLabel = $newItemLabelField.val(),
				$count = $this.parent().prev().children().length,
				optKey = 'utm';

			if ( '' === newItemLabel ) {

				$newItemLabelField.focus();

			} else {

				if ( $this.hasClass('notutm-button') ) optKey = 'notutm';

				var newItem = '<label class="mtsnb-utm-label"><span class="utm-text">'+newItemLabel+' = </span><input type="hidden" name="mtsnb_fields[conditions]['+optKey+'][tags]['+($count+1)+'][name]" id="mtsnb_fields_conditions_'+optKey+'_tags_'+($count+1)+'_name" value="'+newItemLabel+'" /><input type="text" name="mtsnb_fields[conditions]['+optKey+'][tags]['+($count+1)+'][value]" id="mtsnb_fields_conditions_'+optKey+'_tags_'+($count+1)+'_value" value="" /><span class="mtsnb-remove-utm-tag"><i class="fa fa-close"></i></span></label>';

				$this.parent().prev().append( newItem );
				$newItemLabelField.val('');
				$this.prop( "disabled", true );
			}
		});
		// Enable/Disable "Add" button
		$(document).on('keyup', '.mtsnb-add-utm-tag-input', function (event) {
			var $this = $(this),
				val = $this.val(),
				$btn = $this.parent().parent().find('.mtsnb-add-utm-tag');

			if ( '' === val ) {

				$btn.prop( "disabled", true );

			} else {

				$btn.prop( "disabled", false );
			}
		});
		// Remove UTM tag
		$(document).on( 'click', '.mtsnb-remove-utm-tag', function(e) {

			e.preventDefault();

			$(this).parent().remove();
		});

		$(document).on( 'click', '.mtsnb-enable-split-test', function(e) {

			e.preventDefault();

			var $this = $(this);

			if ( $this.hasClass('active') ) {
				$('.mtsnb-b-option').val('');
				$this.text(adminVars.enable_test).removeClass('active');
				$('#b-sub-tabs-wrap').removeClass('active');
				$('#mtsnb-test-stats-a').removeClass('active');
				$('.mtsnb-reset-split-test').removeClass('show');
				
			} else {
				$('.mtsnb-b-option').val('1');
				$this.text(adminVars.disable_test).addClass('active');
				$('#b-sub-tabs-wrap').addClass('active');
				$('#mtsnb-test-stats-a').addClass('active');
				$('.mtsnb-reset-split-test').addClass('show');
			}
		});

		$(document).on( 'click', '.mtsnb-reset-split-test', function(e) {

			e.preventDefault();

			var $this = $(this),
				bar_id = $this.attr('data-bar-id');

			$this.prop( "disabled", true );

			var data = {
				'action': 'mtsnb_reset_ab_stats',
				'bar_id': bar_id
			};
			
			$.post( ajaxurl, data, function( response ) {

				$('#mtsnb-test-stats-a').html( response );
				$('#mtsnb-test-stats-b').html( response );

				$this.prop( "disabled", false );
			});
		});
		

		
		$('#tab-newsletter, #tab-newsletter-b').each(function() {
			var $this = $(this),
				b_prefix = '';

			if ( $this.attr("id") == 'tab-newsletter-b' ) { b_prefix = 'b_'; }

			var $mtsnb_fields_MailChimp_api_key = $this.find('#mtsnb_fields_'+b_prefix+'MailChimp_api_key'),
				$mtsnb_fields_MailChimp_list = $this.find('#mtsnb_fields_'+b_prefix+'MailChimp_list'),
				$mtsnb_fields_aweber_code = $this.find('#mtsnb_fields_'+b_prefix+'aweber_code'),
				$mtsnb_fields_aweber_list = $this.find('#mtsnb_fields_'+b_prefix+'aweber_list'),
				$mtsnb_fields_aweber_consumer_key = $this.find('#mtsnb_fields_'+b_prefix+'aweber_consumer_key'),
				$mtsnb_fields_aweber_consumer_secret = $this.find('#mtsnb_fields_'+b_prefix+'aweber_consumer_secret'),
				$mtsnb_fields_aweber_access_key = $this.find('#mtsnb_fields_'+b_prefix+'aweber_access_key'),
				$mtsnb_fields_aweber_access_secret = $this.find('#mtsnb_fields_'+b_prefix+'aweber_access_secret'),
				$mtsnb_fields_getresponse_api_key = $this.find('#mtsnb_fields_'+b_prefix+'getresponse_api_key'),
				$mtsnb_fields_getresponse_campaign = $this.find('#mtsnb_fields_'+b_prefix+'getresponse_campaign'),
				$mtsnb_fields_campaignmonitor_api_key = $this.find('#mtsnb_fields_'+b_prefix+'campaignmonitor_api_key'),
				$mtsnb_fields_campaignmonitor_list = $this.find('#mtsnb_fields_'+b_prefix+'campaignmonitor_list'),
				$mtsnb_fields_campaignmonitor_client = $this.find('#mtsnb_fields_'+b_prefix+'campaignmonitor_client'),
				$mtsnb_fields_madmimi_api_key = $this.find('#mtsnb_fields_'+b_prefix+'madmimi_api_key'),
				$mtsnb_fields_madmimi_username = $this.find('#mtsnb_fields_'+b_prefix+'madmimi_username'),
				$mtsnb_fields_madmimi_list = $this.find('#mtsnb_fields_'+b_prefix+'madmimi_list'),
				$mtsnb_fields_ActiveCampaign_api_key = $this.find('#mtsnb_fields_'+b_prefix+'active_campaign_api_key'),
				$mtsnb_fields_ActiveCampaign_api_url = $this.find('#mtsnb_fields_'+b_prefix+'active_campaign_api_url'),
				$mtsnb_fields_ActiveCampaign_list = $this.find('#mtsnb_fields_'+b_prefix+'active_campaign_list'),

				$mtsnb_fields_ConstantContact_api_key = $this.find('#mtsnb_fields_'+b_prefix+'constant_contact_api_key'),
				$mtsnb_fields_ConstantContact_token = $this.find('#mtsnb_fields_'+b_prefix+'constant_contact_token'),
				$mtsnb_fields_ConstantContact_list = $this.find('#mtsnb_fields_'+b_prefix+'constant_contact_list'),

				$mtsnb_fields_BenchmarkEmail_username = $this.find('#mtsnb_fields_'+b_prefix+'benchmark_username'),
				$mtsnb_fields_BenchmarkEmail_password = $this.find('#mtsnb_fields_'+b_prefix+'benchmark_password'),
				$mtsnb_fields_BenchmarkEmail_list = $this.find('#mtsnb_fields_'+b_prefix+'benchmark_list'),

				$mtsnb_fields_sendinblue_api = $this.find('#mtsnb_fields_'+b_prefix+'sendinblue_api_key'),
				$mtsnb_fields_sendinblue_list = $this.find('#mtsnb_fields_'+b_prefix+'sendinblue_list'),

				$mtsnb_fields_convertkit_api = $this.find('#mtsnb_fields_'+b_prefix+'convertkit_api_key'),
				$mtsnb_fields_convertkit_list = $this.find('#mtsnb_fields_'+b_prefix+'convertkit_list'),

				$mtsnb_fields_drip_account = $this.find('#mtsnb_fields_'+b_prefix+'drip_account_id'),
				$mtsnb_fields_drip_api = $this.find('#mtsnb_fields_'+b_prefix+'drip_api_key'),
				$mtsnb_fields_drip_list = $this.find('#mtsnb_fields_'+b_prefix+'drip_list');

			//Get all Drip Lists
			$mtsnb_fields_drip_account.keyup(function(){
				setTimeout(function(){
					var drip_account_id = $mtsnb_fields_drip_account.val();
					var drip_api_key = $mtsnb_fields_drip_api.val();
					if(drip_api_key == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-drip-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-drip-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_drip_list);

					var data = {
						'action': 'mtsnb_get_drip_lists',
						'account': drip_account_id,
						'api': drip_api_key
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-drip-get-lists-'+b_prefix+'spinner').remove();
							$mtsnb_fields_drip_list.html(response);
						});
				}, 800);
			});

			$mtsnb_fields_drip_api.keyup(function(){
				setTimeout(function(){
					var drip_account_id = $mtsnb_fields_drip_account.val();
					var drip_api_key = $mtsnb_fields_drip_api.val();
					if(drip_account_id == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-drip-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-drip-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_drip_list);

					var data = {
						'action': 'mtsnb_get_drip_lists',
						'account': drip_account_id,
						'api': drip_api_key
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-drip-get-lists-'+b_prefix+'spinner').remove();
							$mtsnb_fields_drip_list.html(response);
						});
				}, 800);
			});

			//Get all ConvertKit Lists
			$mtsnb_fields_convertkit_api.keyup(function(){
				setTimeout(function(){
					var convertkit_api = $mtsnb_fields_convertkit_api.val();
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-convertkit-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-convertkit-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_convertkit_list);

					var data = {
						'action': 'mtsnb_get_convertkit_lists',
						'api': convertkit_api,
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-convertkit-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_convertkit_list.html(response);
					});
				}, 800);
			});

			//Get all SendInBlue Lists
			$mtsnb_fields_sendinblue_api.keyup(function(){
				setTimeout(function(){
					var be_api = $mtsnb_fields_sendinblue_api.val();
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-sendinblue-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-sendinblue-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_sendinblue_list);

					var data = {
						'action': 'mtsnb_get_sendinblue_email_lists',
						'api': be_api,
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-sendinblue-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_sendinblue_list.html(response);
					});
				}, 800);
			});

			// Get all BenchmarkEmail Lists
			$mtsnb_fields_BenchmarkEmail_username.keyup(function(){
				setTimeout(function(){
					var be_username = $mtsnb_fields_BenchmarkEmail_username.val();
					var be_password = $mtsnb_fields_BenchmarkEmail_password.val();
					if(be_password == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_BenchmarkEmail_list);

					var data = {
						'action': 'mtsnb_get_benchmark_email_lists',
						'username': be_username,
						'password': be_password
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_BenchmarkEmail_list.html(response);
					});
				}, 800);
			});

			$mtsnb_fields_BenchmarkEmail_password.keyup(function(){
				setTimeout(function(){
					var be_username = $mtsnb_fields_BenchmarkEmail_username.val();
					var be_password = $mtsnb_fields_BenchmarkEmail_password.val();
					if(be_username == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_BenchmarkEmail_list);

					var data = {
						'action': 'mtsnb_get_benchmark_email_lists',
						'username': be_username,
						'password': be_password
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_BenchmarkEmail_list.html(response);
					});
				}, 800);
			});

      // Get all MailChimp Lists
			$mtsnb_fields_MailChimp_api_key.keyup(function(){

				// Do nothing if we are already retrieve the lists
				if ($('#mtsnb-MailChimp-get-lists-'+b_prefix+'spinner').length != 0) {
					return;
				}

				$('<i id="mtsnb-MailChimp-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_MailChimp_list);

				var data = {
					'action': 'mtsnb_get_mailchimp_lists',
					'api_key': $mtsnb_fields_MailChimp_api_key.val()
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-MailChimp-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_MailChimp_list.html(response);
				});
			});

			// Get all ActiveCampaign Lists
			$mtsnb_fields_ActiveCampaign_api_key.keyup(function(){
				setTimeout(function(){
					var ac_api_key = $mtsnb_fields_ActiveCampaign_api_key.val();
					var ac_api_url = $mtsnb_fields_ActiveCampaign_api_url.val();
					if(ac_api_url == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-active_campaign-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-active_campaign-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ActiveCampaign_list);

					var data = {
						'action': 'mtsnb_get_active_campaign_lists',
						'api_key': ac_api_key,
						'api_url': ac_api_url
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-active_campaign-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ActiveCampaign_list.html(response);
					});
				}, 800);
			});

			$mtsnb_fields_ActiveCampaign_api_url.keyup(function(){
				setTimeout(function(){
					var ac_api_key = $mtsnb_fields_ActiveCampaign_api_key.val();
					var ac_api_url = $mtsnb_fields_ActiveCampaign_api_url.val();
					if(ac_api_key == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-active_campaign-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-active_campaign-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ActiveCampaign_list);

					var data = {
						'action': 'mtsnb_get_active_campaign_lists',
						'api_key': ac_api_key,
						'api_url': ac_api_url
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-active_campaign-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ActiveCampaign_list.html(response);
					});
				}, 800);
			});

			// Get all ConstantContact Lists
			$mtsnb_fields_ConstantContact_api_key.keyup(function(){
				setTimeout(function(){
					var cc_api_key = $mtsnb_fields_ConstantContact_api_key.val();
					var token = $mtsnb_fields_ConstantContact_token.val();
					if(token == '') return;
					
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-constant_contact-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ConstantContact_list);

					var data = {
						'action': 'mtsnb_get_constant_contact_lists',
						'api_key': cc_api_key,
						'token': token
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ConstantContact_list.html(response);
					}).fail(function(){
						$('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ConstantContact_list.html('');
					});
				}, 800);
			});

			$mtsnb_fields_ConstantContact_token.keyup(function(){
				setTimeout(function(){
					var cc_api_key = $mtsnb_fields_ConstantContact_api_key.val();
					var token = $mtsnb_fields_ConstantContact_token.val();
					if(cc_api_key == '') return;
					// Do nothing if we are already retrieve the lists
					if ($('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').length != 0) {
						return;
					}

					$('<i id="mtsnb-constant_contact-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ConstantContact_list);

					var data = {
						'action': 'mtsnb_get_constant_contact_lists',
						'api_key': cc_api_key,
						'token': token
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ConstantContact_list.html(response);
					}).fail(function(){
						$('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ConstantContact_list.html('');
					});
				}, 800);
				
			});

			// Get Aweber Lists
			$('.mtsnb-aweber-connect').click(function(){
				$mtsnb_fields_aweber_code.parent('div').parent('div').removeClass('hidden');
				$mtsnb_fields_aweber_code.removeClass('hidden');
			});

			$mtsnb_fields_aweber_code.keyup(function (){

				// Do nothing if we are already retrieve the lists
				if ($('#mtsnb-aweber-get-lists-'+b_prefix+'spinner').length != 0) {
					return;
				}

				// Do nothing if the user did not input a code
				if ($mtsnb_fields_aweber_code.val() == '') {
					return;
				}

				$mtsnb_fields_aweber_list.html('');

				$('<i id="mtsnb-aweber-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_aweber_list);

				var data = {
					'action': 'mtsnb_get_aweber_lists',
					'consumer_key': $mtsnb_fields_aweber_consumer_key.val(),
					'consumer_secret': $mtsnb_fields_aweber_consumer_secret.val(),
					'access_key': $mtsnb_fields_aweber_access_key.val(),
					'access_secret': $mtsnb_fields_aweber_access_secret.val(),
					'code': $mtsnb_fields_aweber_code.val(),
				};
				
				$.post( ajaxurl, data, function(response) {

					response = $.parseJSON(response);

					$('#mtsnb-aweber-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_aweber_list.html(response.html);
					$mtsnb_fields_aweber_consumer_key.val(response.consumer_key);
					$mtsnb_fields_aweber_consumer_secret.val(response.consumer_secret);
					$mtsnb_fields_aweber_access_key.val(response.access_key);
					$mtsnb_fields_aweber_access_secret.val(response.access_secret);

					if (response.consumer_key != '') {
						$mtsnb_fields_aweber_code.addClass('hidden');
					}

				});
			});

			// Get all Get Response Lists
			$mtsnb_fields_getresponse_api_key.keyup(function(){

				// Do nothing if we are already retrieve the lists
				if ($('#mtsnb-getresponse-get-lists-'+b_prefix+'spinner').length != 0) {
					return;
				}

				$('<i id="mtsnb-getresponse-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_getresponse_campaign);

				var data = {
					'action': 'mtsnb_get_getresponse_lists',
					'api_key': $mtsnb_fields_getresponse_api_key.val()
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-getresponse-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_getresponse_campaign.html(response);
				});
			});

			// Get all Campaign Monitor Clients and Lists
			$mtsnb_fields_campaignmonitor_api_key.keyup(function(){

				// Do nothing if we are already retrieve the lists
				if ($('.mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner').length != 0) {
					return;
				}

				$('<i class="mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_campaignmonitor_list);
				$('<i class="mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_campaignmonitor_client);

				var data = {
					'action': 'mtsnb_get_campaignmonitor_lists',
					'api_key': $mtsnb_fields_campaignmonitor_api_key.val(),
				};
				
				$.post( ajaxurl, data, function(response) {

					response = $.parseJSON(response);

					$('.mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_campaignmonitor_client.html(response.clients);
					$mtsnb_fields_campaignmonitor_list.html(response.lists);
				});
			});

			// Update lists for Campaign Monitor
			$mtsnb_fields_campaignmonitor_client.change(function(){

				// Do nothing if we are already retrieve the lists
				if ($('.mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner').length != 0) {
					return;
				}

				$('<i class="mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_campaignmonitor_list);

				var data = {
					'action': 'mtsnb_update_campaignmonitor_lists',
					'api_key': $mtsnb_fields_campaignmonitor_api_key.val(),
					'client_id': $(this).val(),
				};
				
				$.post( ajaxurl, data, function(response) {
					$('.mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_campaignmonitor_list.html(response);
				});
			});

			// Get all Mad Mimi Lists
			$mtsnb_fields_madmimi_api_key.keyup(function(){

				// Do nothing if we are already retrieve the lists
				if ($('#mtsnb-madmimi-get-lists-'+b_prefix+'spinner').length != 0) {
					return;
				}

				$('<i id="mtsnb-madmimi-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_madmimi_list);

				var data = {
					'action': 'mtsnb_get_madmimi_lists',
					'api_key': $mtsnb_fields_madmimi_api_key.val(),
					'username': $mtsnb_fields_madmimi_username.val(),
					'list': $mtsnb_fields_madmimi_list.val()
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-madmimi-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_madmimi_list.html(response);
				});
			});

			// Async Functions
			setTimeout(function() {

				// Campaign Monitor
				$('<i class="mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_campaignmonitor_list);
				$('<i class="mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_campaignmonitor_client);

				var data = {
					'action': 'mtsnb_get_campaignmonitor_lists',
					'api_key': $mtsnb_fields_campaignmonitor_api_key.val(),
					'client': $mtsnb_fields_campaignmonitor_client.attr('data-client'),
					'list': $mtsnb_fields_campaignmonitor_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {

					response = $.parseJSON(response);

					$('.mtsnb-campaignmonitor-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_campaignmonitor_client.html(response.clients);
					$mtsnb_fields_campaignmonitor_list.html(response.lists);

				});

				// MailChimp
				$('<i id="mtsnb-MailChimp-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_MailChimp_list);

				var data = {
					'action': 'mtsnb_get_mailchimp_lists',
					'api_key': $mtsnb_fields_MailChimp_api_key.val(),
					'list': $mtsnb_fields_MailChimp_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-MailChimp-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_MailChimp_list.html(response);
				});


				//ActiveCampaign

				var ac_api_key = $mtsnb_fields_ActiveCampaign_api_key.val();
				var ac_api_url = $mtsnb_fields_ActiveCampaign_api_url.val();
				if(ac_api_key !== '' && ac_api_url !== '') {
					$('<i id="mtsnb-active_campaign-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ActiveCampaign_list);

					var data = {
						'action': 'mtsnb_get_active_campaign_lists',
						'api_key': ac_api_key,
						'api_url': ac_api_url,
						'list': $mtsnb_fields_ActiveCampaign_list.attr('data-list'),
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-active_campaign-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ActiveCampaign_list.html(response);
					});
				}

				//ConstantContact

				var cc_api_key = $mtsnb_fields_ConstantContact_api_key.val();
				var token = $mtsnb_fields_ConstantContact_token.val();
				if(cc_api_key !== '' && token !== '') {
					$('<i id="mtsnb-constant_contact-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ConstantContact_list);

					var data = {
						'action': 'mtsnb_get_constant_contact_lists',
						'api_key': cc_api_key,
						'token': token,
						'list': $mtsnb_fields_ConstantContact_list.attr('data-list'),
					};
					
					$.post( ajaxurl, data, function(response) {
						$('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ConstantContact_list.html(response);
					}).fail(function(){
						$('#mtsnb-constant_contact-get-lists-'+b_prefix+'spinner').remove();
						$mtsnb_fields_ConstantContact_list.html('');
					});
				}

				// Aweber
				$mtsnb_fields_aweber_list.html('');

				$('<i id="mtsnb-aweber-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_aweber_list);

				var data = {
					'action': 'mtsnb_get_aweber_lists',
					'consumer_key': $mtsnb_fields_aweber_consumer_key.val(),
					'consumer_secret': $mtsnb_fields_aweber_consumer_secret.val(),
					'access_key': $mtsnb_fields_aweber_access_key.val(),
					'access_secret': $mtsnb_fields_aweber_access_secret.val(),
					'code': $mtsnb_fields_aweber_code.val(),
					'list': $mtsnb_fields_aweber_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {

					response = $.parseJSON(response);

					$('#mtsnb-aweber-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_aweber_list.html(response.html);

					$mtsnb_fields_aweber_consumer_key.val(response.consumer_key);
					$mtsnb_fields_aweber_consumer_secret.val(response.consumer_secret);
					$mtsnb_fields_aweber_access_key.val(response.access_key);
					$mtsnb_fields_aweber_access_secret.val(response.access_secret);

					if (response.consumer_key != '') {
						$mtsnb_fields_aweber_code.addClass('hidden');
					}

				});

				// Get Response
				$('<i id="mtsnb-getresponse-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_getresponse_campaign);

				var data = {
					'action': 'mtsnb_get_getresponse_lists',
					'api_key': $mtsnb_fields_getresponse_api_key.val(),
					'campaign': $mtsnb_fields_getresponse_campaign.attr('data-list')
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-getresponse-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_getresponse_campaign.html(response);
				});

				// Mad Mimi
				$('<i id="mtsnb-madmimi-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_madmimi_list);

				var data = {
					'action': 'mtsnb_get_madmimi_lists',
					'api_key': $mtsnb_fields_madmimi_api_key.val(),
					'username': $mtsnb_fields_madmimi_username.val(),
					'list': $mtsnb_fields_madmimi_list.attr('data-list')
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-madmimi-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_madmimi_list.html(response);
				});

			} ,1);
				
			//ConvertKit
			var convertkit_api = $this.find('#mtsnb_fields_'+b_prefix+'convertkit_api_key').val();
			if(convertkit_api !== '') {
				$('<i id="mtsnb-convertkit-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_convertkit_list);

				var data = {
					'action': 'mtsnb_get_convertkit_lists',
					'api': convertkit_api,
					'list': $mtsnb_fields_convertkit_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-convertkit-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_convertkit_list.html(response);
				});
			}

			//SendInBlue
			var sendinblue_api = $this.find('#mtsnb_fields_'+b_prefix+'sendinblue_api_key').val();
			if(sendinblue_api !== '') {
				$('<i id="mtsnb-sendinblue-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_sendinblue_list);

				var data = {
					'action': 'mtsnb_get_sendinblue_email_lists',
					'api': sendinblue_api,
					'list': $mtsnb_fields_sendinblue_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-sendinblue-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_sendinblue_list.html(response);
				});
			}
			
			//Drip
			var drip_account_id = $mtsnb_fields_drip_account.val(),
					drip_api_key = $mtsnb_fields_drip_api.val();
			if(drip_account_id !== '' && drip_api_key !== '') {
				$('<i id="mtsnb-drip-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_drip_list);

				var data = {
					'action': 'mtsnb_get_drip_lists',
					'account': drip_account_id,
					'api': drip_api_key,
					'list': $mtsnb_fields_drip_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-drip-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_drip_list.html(response);
				});
			}
			
			//BenchmarkEmail
			var be_username = $mtsnb_fields_BenchmarkEmail_username.val();
			var be_password = $mtsnb_fields_BenchmarkEmail_password.val();
			if(be_username !== '' && be_password !== '') {
				$('<i id="mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner" class="fa fa-spinner fa-spin"></i>').insertAfter($mtsnb_fields_ActiveCampaign_list);

				var data = {
					'action': 'mtsnb_get_benchmark_email_lists',
					'username': be_username,
					'password': be_password,
					'list': $mtsnb_fields_BenchmarkEmail_list.attr('data-list'),
				};
				
				$.post( ajaxurl, data, function(response) {
					$('#mtsnb-benchmark_email-get-lists-'+b_prefix+'spinner').remove();
					$mtsnb_fields_BenchmarkEmail_list.html(response);
				});
			}

		});
		
		function format_colors_dropdown(state) {
			var palette = '<div class="single-palette"><table class="color-palette"><tbody><tr>';
			$.each($(state.element).data('colors'), function(index, val) {
				 palette += '<td style="background-color: '+val+'">&nbsp;</td>';
			});
			palette += '</tr></tbody></table></div>';
			return state.text + palette;
		}
		
		$('.mtsnb-colors-select').select2({
			placeholder: "Select predefined color set",
			allowClear: true,
			formatResult: format_colors_dropdown,
			escapeMarkup: function(m) { return m; },
			minimumResultsForSearch: 10
		}).change(function(event) {
			var $el = $(this).find(':selected');
			if (!$el.val()) return;
			
			var target = $el.data('target');
			$.each($el.data('colors'), function(index, val) {
				$('#'+target+'_'+index+'_color').iris('color', val);
			});
		});

		var slideraVal = $('.mtsnb-ab-slider-a-option').val();
		var sliderbVal = $('.mtsnb-ab-slider-b-option').val();

		$('.mtsnb-ab-slider').slider();

		$('.mtsnb-ab-slider-a').slider({
			value: slideraVal,
			min: 1,
			max: 99,
			slide: function(event, ui) {
				$('.mtsnb-a-slider-num').text(ui.value);
				$('.mtsnb-ab-slider-a-option').val(ui.value);

				$('.mtsnb-ab-slider-b').slider( "option", "value", 100 - ui.value );
				$('.mtsnb-b-slider-num').text( 100 - ui.value);
				$('.mtsnb-ab-slider-b-option').val( 100 - ui.value);
			}
		});

		$('.mtsnb-ab-slider-b').slider({
			value: sliderbVal,
			min: 1,
			max: 99,
			slide: function(event, ui) {
				$('.mtsnb-b-slider-num').text(ui.value);
				$('.mtsnb-ab-slider-b-option').val(ui.value);

				$('.mtsnb-ab-slider-a').slider( "option", "value", 100 - ui.value );
				$('.mtsnb-a-slider-num').text( 100 - ui.value);
				$('.mtsnb-ab-slider-a-option').val( 100 - ui.value);
			}
		});
	});

	$(document).on('click', '#mtsnb-settings-tabs li a', function(e) {
		e.preventDefault();
		var $this = $(this);
		if(!($this).parent().hasClass('active')) {
			$('#mtsnb-settings-tabs .active').removeClass('active');
			$this.parent().addClass('active');
			$('.mtsnb-tab-content.active').removeClass('active');
			$($this.attr('href')).addClass('active');
		}
		return false;
	});

	$(document).on('click', '.mtsnb-export-actions .mtsnb-show-code', function(e){
		e.preventDefault();
		$(this).next('textarea').slideToggle();
		return false;
	});

	$(document).on('click', '.mtsnb-import-options-wrapper .mtsnb-show-import-code', function(e){
		e.preventDefault();
		$(this).parents('.mtsnb-import-options-wrapper').removeClass('show-file').toggleClass('show-code');
		return false;
	});

	$(document).on('click', '.mtsnb-import-options-wrapper .mtsnb-upload-file', function(e){
		e.preventDefault();
		$(this).parents('.mtsnb-import-options-wrapper').removeClass('show-code').toggleClass('show-file');
		return false;
	});

	$(document).on('click', '.mtsnb-import-options-wrapper #mtsnb-import-button', function(e){
		e.preventDefault();
		if($(this).parents('.mtsnb-import-options-wrapper').hasClass('show-file')) {
			var $fd = new FormData(),
					file_obj = $('#mtsnb-file-import'),
					files = file_obj[0].files;
			jQuery.each(files, function(i, file){
				$fd.append("files["+i+"]", file);
			});
			$fd.append('action', 'mtsnb_import_posts_data');
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: $fd,
				processData: false,
				contentType: false,
				success: function(data) {
					alert(data+' Notification Bar Imported!');
				}
			});
		} else if($(this).parents('.mtsnb-import-options-wrapper').hasClass('show-code')) {
			var data = $('#mtsnb-import-data').val();
			var $fd = {
				'action' : 'mtsnb_import_posts_data',
				'data' : data
			};
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: $fd,
				success: function(data) {
					alert(data+' Notification Bar Imported!');
				}
			});
		}
		return false;
	});

	$(document).on('change', '.mtsnb-notification-content input', function(e){
		e.preventDefault();
		if( 'all' === $(this).val() ) {
			$(this).parents('ul').find('input:checkbox').not(this).prop('checked', this.checked);
		}
		if($(this).parents('ul').find('input:checked').length > 0) {
			var checkedValues = [],
				codetextarea = $('textarea#export-mtsnb-data'),
				base_url = $('#mtsnb-download-data').data('basehref'),
				include_vars = '';
			codetextarea.text('...');
			$(this).parents('ul').find('input:checked').each(function() {
				if( 'all' !== $(this).val()) {
					checkedValues.push($(this).val());
					include_vars += $(this).val() + ',';
				}
			});
			$.ajax({
				type: 'post',
				url: ajaxurl,
				data: {
					action: 'mtsnb_get_posts_data',
					selected: checkedValues
				},
				dataType: 'html',
				success: function(data) {
					codetextarea.text(data);
				}
			});
			if(include_vars) {
				base_url = base_url+'&post_ids='+include_vars;
			}
			$('#mtsnb-download-data').attr('href', base_url);
		}
		return false;
	});

	$(document).on('change', '.mtsnb-import-demo-wrapper input', function(e){
		e.preventDefault();
		if( 'all' === $(this).val() ) {
			$(this).parents('ul').find('input:checkbox').not(this).prop('checked', this.checked);
		}
		return false;
	});

	$(document).on('click', '#mtsnb-import-demo-content', function(e){
		e.preventDefault();
		var $this = $(this),
				org_text = $this.text(),
				checkedValues = [];

		$this.text('Processing...');
		$(this).parents('.mtsnb-import-demo-wrapper').find('input:checked').each(function() {
			if( 'all' !== $(this).val()) {
				checkedValues.push($(this).val());
			}
		});
  
		$.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'mtsnb_import_demo',
				'types': checkedValues
			},
			success: function(data) {
				alert(data+' Notification Bar Imported!');
				if(data) {
					$this.text('Completed!');	
				} else {
					$this.text('Failed!');
				}
				setTimeout( function() {
					$this.text( org_text );
				}, 2000 );
			}
		});
		return false;
	});

})( jQuery );
