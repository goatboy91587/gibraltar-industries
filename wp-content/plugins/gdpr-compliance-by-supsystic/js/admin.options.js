var grsAdminFormChanged = [];
window.onbeforeunload = function(){
	// If there are at lease one unsaved form - show message for confirnation for page leave
	if(grsAdminFormChanged.length)
		return 'Some changes were not-saved. Are you sure you want to leave?';
};
jQuery(document).ready(function(){
	grsInitMainPromoPopup();
	if(typeof(grsActiveTab) != 'undefined' && grsActiveTab != 'main_page' && jQuery('#toplevel_page_gdpr-compliance-by-supsystic').hasClass('wp-has-current-submenu')) {
		var subMenus = jQuery('#toplevel_page_gdpr-compliance-by-supsystic').find('.wp-submenu li');
		subMenus.removeClass('current').each(function(){
			if(jQuery(this).find('a[href$="&tab='+ grsActiveTab+ '"]').length) {
				jQuery(this).addClass('current');
			}
		});
	}
	
	// Timeout - is to count only user changes, because some changes can be done auto when form is loaded
	setTimeout(function() {
		// If some changes was made in those forms and they were not saved - show message for confirnation before page reload
		var formsPreventLeave = [];
		if(formsPreventLeave && formsPreventLeave.length) {
			jQuery('#'+ formsPreventLeave.join(', #')).find('input,select').change(function(){
				var formId = jQuery(this).parents('form:first').attr('id');
				changeAdminFormGrs(formId);
			});
			jQuery('#'+ formsPreventLeave.join(', #')).find('input[type=text],textarea').keyup(function(){
				var formId = jQuery(this).parents('form:first').attr('id');
				changeAdminFormGrs(formId);
			});
			jQuery('#'+ formsPreventLeave.join(', #')).submit(function(){
				adminFormSavedGrs( jQuery(this).attr('id') );
			});
		}
	}, 1000);

	if(jQuery('.grsInputsWithDescrForm').length) {
		jQuery('.grsInputsWithDescrForm').find('input[type=checkbox][data-optkey]').change(function(){
			var optKey = jQuery(this).data('optkey')
			,	descShell = jQuery('#grsFormOptDetails_'+ optKey);
			if(descShell.size()) {
				if(jQuery(this).attr('checked')) {
					descShell.slideDown( 300 );
				} else {
					descShell.slideUp( 300 );
				}
			}
		}).trigger('change');
	}
	grsInitStickyItem();
	grsInitCustomCheckRadio();
	//grsInitCustomSelect();
	
	jQuery('.grsFieldsetToggled').each(function(){
		var self = this;
		jQuery(self).find('.grsFieldsetContent').hide();
		jQuery(self).find('.grsFieldsetToggleBtn').click(function(){
			var icon = jQuery(this).find('i')
			,	show = icon.hasClass('fa-plus');
			show ? icon.removeClass('fa-plus').addClass('fa-minus') : icon.removeClass('fa-minus').addClass('fa-plus');
			jQuery(self).find('.grsFieldsetContent').slideToggle( 300, function(){
				if(show) {
					jQuery(this).find('textarea').each(function(i, el){
						if(typeof(this.CodeMirrorEditor) !== 'undefined') {
							this.CodeMirrorEditor.refresh();
						}
					});
				}
			} );
			return false;
		});
	});
	// Go to Top button init
	if(jQuery('#grsPopupGoToTopBtn').length) {
		jQuery('#grsPopupGoToTopBtn').click(function(){
			jQuery('html, body').animate({
				scrollTop: 0
			}, 1000);
			jQuery(this).parents('#grsPopupGoToTop:first').hide();
			return false;
		});
	}
	// Tooltipster initialization
	/*var tooltipsterSettings = {
		contentAsHTML: true
	,	interactive: true
	,	speed: 0
	,	delay: 0
	//,	animation: 'swing'
	,	maxWidth: 450
	};
	if(jQuery('.supsystic-tooltip').length) {
		tooltipsterSettings.position = 'top-left';
		jQuery('.supsystic-tooltip').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-bottom').length) {
		tooltipsterSettings.position = 'bottom-left';
		jQuery('.supsystic-tooltip-bottom').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-left').length) {
		tooltipsterSettings.position = 'left';
		jQuery('.supsystic-tooltip-left').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-right').length) {
		tooltipsterSettings.position = 'right';
		jQuery('.supsystic-tooltip-right').tooltipster( tooltipsterSettings );
	}*/
	grsInitTooltips();
	if(jQuery('.grsCopyTextCode').length) {
		setTimeout(function(){	// Give it some time - wait until all other elements will be initialized
			var cloneWidthElement =  jQuery('<span class="sup-shortcode" />').appendTo('.supsystic-plugin');
			jQuery('.grsCopyTextCode').attr('readonly', 'readonly').click(function(){
				this.setSelectionRange(0, this.value.length);
			}).focus(function(){
				this.setSelectionRange(0, this.value.length);
			});
			jQuery('input.grsCopyTextCode').each(function(){
				cloneWidthElement.html( str_replace(jQuery(this).val(), '<', 'P') );
				var parentSelector = jQuery(this).data('parent-selector')
				,	parentWidth = (parentSelector && parentSelector != '' 
						? jQuery(this).parents(parentSelector+ ':first') 
						: jQuery(this).parent()
					).width()
				,	txtWidth = cloneWidthElement.width();
				if(parentWidth <= 0 || parentWidth > txtWidth) {
					jQuery(this).width( cloneWidthElement.width() );
				}
			});
			cloneWidthElement.remove();
		}, 500);
	}
	// Check for showing review notice after a week usage
    grsInitPlugNotices();
	jQuery(".supsystic-plugin .tooltipstered").removeAttr("title");
});
function grsInitTooltips( selector ) {
	var tooltipsterSettings = {
		contentAsHTML: true
	,	interactive: true
	,	speed: 0
	,	delay: 0
	//,	animation: 'swing'
	,	maxWidth: 450
	}
	,	findPos = {
		'.supsystic-tooltip': 'top-left'
	,	'.supsystic-tooltip-bottom': 'bottom-left'
	,	'.supsystic-tooltip-left': 'left'
	,	'.supsystic-tooltip-right': 'right'
	}
	,	$findIn = selector ? jQuery( selector ) : false;
	for(var k in findPos) {
		if(typeof(k) === 'string') {
			var $tips = $findIn ? $findIn.find( k ) : jQuery( k ).not('.sup-no-init');
			if($tips && $tips.size()) {
				tooltipsterSettings.position = findPos[ k ];
				// Fallback for case if library was not loaded
				if(!$tips.tooltipster) continue;
				$tips.tooltipster( tooltipsterSettings );
			}
		}
	}
}
function changeAdminFormGrs(formId) {
	if(jQuery.inArray(formId, grsAdminFormChanged) == -1)
		grsAdminFormChanged.push(formId);
}
function adminFormSavedGrs(formId) {
	if(grsAdminFormChanged.length) {
		for(var i in grsAdminFormChanged) {
			if(grsAdminFormChanged[i] == formId) {
				grsAdminFormChanged.pop(i);
			}
		}
	}
}
function checkAdminFormSaved() {
	if(grsAdminFormChanged.length) {
		if(!confirm(toeLangGrs('Some changes were not-saved. Are you sure you want to leave?'))) {
			return false;
		}
		grsAdminFormChanged = [];	// Clear unsaved forms array - if user wanted to do this
	}
	return true;
}
function isAdminFormChanged(formId) {
	if(grsAdminFormChanged.length) {
		for(var i in grsAdminFormChanged) {
			if(grsAdminFormChanged[i] == formId) {
				return true;
			}
		}
	}
	return false;
}
/*Some items should be always on users screen*/
function grsInitStickyItem() {
	jQuery(window).scroll(function(){
		var stickiItemsSelectors = [/*'.ui-jqgrid-hdiv', */'.supsystic-sticky']
		,	elementsUsePaddingNext = [/*'.ui-jqgrid-hdiv', */'.supsystic-bar']	// For example - if we stick row - then all other should not offest to top after we will place element as fixed
		,	wpTollbarHeight = 32
		,	wndScrollTop = jQuery(window).scrollTop() + wpTollbarHeight
		,	footer = jQuery('.grsAdminFooterShell')
		,	footerHeight = footer && footer.size() ? footer.height() : 0
		,	docHeight = jQuery(document).height()
		,	wasSticking = false
		,	wasUnSticking = false;
		/*if(jQuery('#wpbody-content .update-nag').length) {	// Not used for now
			wpTollbarHeight += parseInt(jQuery('#wpbody-content .update-nag').outerHeight());
		}*/
		for(var i = 0; i < stickiItemsSelectors.length; i++) {
			jQuery(stickiItemsSelectors[ i ]).each(function(){
				var element = jQuery(this);
				if(element && element.size() && !element.hasClass('sticky-ignore')) {
					var scrollMinPos = element.offset().top
					,	prevScrollMinPos = parseInt(element.data('scrollMinPos'))
					,	useNextElementPadding = toeInArray(stickiItemsSelectors[ i ], elementsUsePaddingNext) !== -1 || element.hasClass('sticky-padd-next')
					,	currentScrollTop = wndScrollTop
					,	calcPrevHeight = element.data('prev-height')
					,	currentBorderHeight = wpTollbarHeight
					,	usePrevHeight = 0;
					if(calcPrevHeight) {
						usePrevHeight = jQuery(calcPrevHeight).outerHeight();
						currentBorderHeight += usePrevHeight;
					}
					if(currentScrollTop > scrollMinPos && !element.hasClass('supsystic-sticky-active')) {	// Start sticking
						if(element.hasClass('sticky-save-width')) {
							element.width( element.width() );
							//element.addClass('sticky-full-width');
						}
						element.addClass('supsystic-sticky-active').data('scrollMinPos', scrollMinPos).css({
							'top': currentBorderHeight
						});
						if(useNextElementPadding) {
							//element.addClass('supsystic-sticky-active-bordered');
							var nextElement = element.next();
							if(nextElement && nextElement.size()) {
								nextElement.data('prevPaddingTop', nextElement.css('padding-top'));
								var addToNextPadding = parseInt(element.data('next-padding-add'));
								addToNextPadding = addToNextPadding ? addToNextPadding : 0;
								nextElement.css({
									'padding-top': (element.hasClass('sticky-outer-height') ? element.outerHeight() : element.height()) + usePrevHeight + addToNextPadding
								});
							}
						}
						wasSticking = true;
						element.trigger('startSticky');
					} else if(!isNaN(prevScrollMinPos) && currentScrollTop <= prevScrollMinPos) {	// Stop sticking
						element.removeClass('supsystic-sticky-active').data('scrollMinPos', 0).css({
							//'top': 0
						});
						if(element.hasClass('sticky-save-width')) {
							if(element.hasClass('sticky-base-width-auto')) {
								element.css('width', 'auto');
							}
							//element.removeClass('sticky-full-width');
						}
						if(useNextElementPadding) {
							//element.removeClass('supsystic-sticky-active-bordered');
							var nextElement = element.next();
							if(nextElement && nextElement.size()) {
								var nextPrevPaddingTop = parseInt(nextElement.data('prevPaddingTop'));
								if(isNaN(nextPrevPaddingTop))
									nextPrevPaddingTop = 0;
								nextElement.css({
									'padding-top': nextPrevPaddingTop
								});
							}
						}
						element.trigger('stopSticky');
						wasUnSticking = true;
					} else {	// Check new stick position
						if(element.hasClass('supsystic-sticky-active')) {
							if(footerHeight) {
								var elementHeight = element.height()
								,	heightCorrection = 32
								,	topDiff = docHeight - footerHeight - (currentScrollTop + elementHeight + heightCorrection);
								if(topDiff < 0) {
									element.css({
										'top': currentBorderHeight + topDiff
									});
								} else {
									element.css({
										'top': currentBorderHeight
									});
								}
							}
							// If at least on element is still sticking - count it as all is working
							wasSticking = wasUnSticking = false;
						}
					}
				}
			});
		}
		if(wasSticking) {
			if(jQuery('#grsPopupGoToTop').length)
				jQuery('#grsPopupGoToTop').show();
		} else if(wasUnSticking) {
			if(jQuery('#grsPopupGoToTop').length)
				jQuery('#grsPopupGoToTop').hide();
		}
	});
}
function grsInitCustomCheckRadio(selector) {
	if(!jQuery.fn.iCheck) return;
	if(!selector)
		selector = document;
	jQuery(selector).find('input').iCheck('destroy').iCheck({
		checkboxClass: 'icheckbox_minimal'
	,	radioClass: 'iradio_minimal'
	}).on('ifChanged', function(e){
		// for checkboxHiddenVal type, see class htmlGrs
		jQuery(this).trigger('change');
		if(jQuery(this).hasClass('cbox')) {
			var parentRow = jQuery(this).parents('.jqgrow:first');
			if(parentRow && parentRow.size()) {
				jQuery(this).parents('td:first').trigger('click');
			} else {
				var checkId = jQuery(this).attr('id');
				if(checkId && checkId != '' && strpos(checkId, 'cb_') === 0) {
					var parentTblId = str_replace(checkId, 'cb_', '');
					if(parentTblId && parentTblId != '' && jQuery('#'+ parentTblId).length) {
						jQuery('#'+ parentTblId).find('input[type=checkbox]').iCheck('update');
					}
				}
			}
		}
	}).on('ifClicked', function(e){
		jQuery(this).trigger('click');
	});
}
function grsCheckDestroy(checkbox) {
	if(!jQuery.fn.iCheck) return;
	jQuery(checkbox).iCheck('destroy');
}
function grsCheckDestroyArea(selector) {
	if(!jQuery.fn.iCheck) return;
	jQuery(selector).find('input[type=checkbox]').iCheck('destroy');
}
function grsCheckUpdate(checkbox) {
	if(!jQuery.fn.iCheck) return;
	jQuery(checkbox).iCheck('update');
}
function grsCheckUpdateArea(selector) {
	if(!jQuery.fn.iCheck) return;
	jQuery(selector).find('input[type=checkbox]').iCheck('update');
}
function grsGetTxtEditorVal(id) {
	if(typeof(tinyMCE) !== 'undefined' 
		&& tinyMCE.get( id ) 
		&& !jQuery('#'+ id).is(':visible') 
		&& tinyMCE.get( id ).getDoc 
		&& typeof(tinyMCE.get( id ).getDoc) == 'function' 
		&& tinyMCE.get( id ).getDoc()
	)
		return tinyMCE.get( id ).getContent();
	else
		return jQuery('#'+ id).val();
}
function grsSetTxtEditorVal(id, content) {
	if(typeof(tinyMCE) !== 'undefined' 
		&& tinyMCE 
		&& tinyMCE.get( id ) 
		&& !jQuery('#'+ id).is(':visible')
		&& tinyMCE.get( id ).getDoc 
		&& typeof(tinyMCE.get( id ).getDoc) == 'function' 
		&& tinyMCE.get( id ).getDoc()
	)
		tinyMCE.get( id ).setContent(content);
	else
		jQuery('#'+ id).val( content );
}
/**
 * Add data to jqGrid object post params search
 * @param {object} param Search params to set
 * @param {string} gridSelectorId ID of grid table html element
 */
function grsGridSetListSearch(param, gridSelectorId) {
	jQuery('#'+ gridSelectorId).setGridParam({
		postData: {
			search: param
		}
	});
}
/**
 * Set data to jqGrid object post params search and trigger search
 * @param {object} param Search params to set
 * @param {string} gridSelectorId ID of grid table html element
 */
function grsGridDoListSearch(param, gridSelectorId) {
	grsGridSetListSearch(param, gridSelectorId);
	jQuery('#'+ gridSelectorId).trigger( 'reloadGrid' );
}
/**
 * Get row data from jqGrid
 * @param {number} id Item ID (from database for example)
 * @param {string} gridSelectorId ID of grid table html element
 * @return {object} Row data
 */
function grsGetGridDataById(id, gridSelectorId) {
	var rowId = getGridRowId(id, gridSelectorId);
	if(rowId) {
		return jQuery('#'+ gridSelectorId).jqGrid ('getRowData', rowId);
	}
	return false;
}
/**
 * Get cell data from jqGrid
 * @param {number} id Item ID (from database for example)
 * @param {string} column Column name
 * @param {string} gridSelectorId ID of grid table html element
 * @return {string} Cell data
 */
function grsGetGridColDataById(id, column, gridSelectorId) {
	var rowId = getGridRowId(id, gridSelectorId);
	if(rowId) {
		return jQuery('#'+ gridSelectorId).jqGrid ('getCell', rowId, column);
	}
	return false;
}
/**
 * Get grid row ID (ID of table row) from item ID (from database ID for example)
 * @param {number} id Item ID (from database for example)
 * @param {string} gridSelectorId ID of grid table html element
 * @return {number} Table row ID
 */
function getGridRowId(id, gridSelectorId) {
	var rowId = parseInt(jQuery('#'+ gridSelectorId).find('[aria-describedby='+ gridSelectorId+ '_id][title='+ id+ ']').parent('tr:first').index());
	if(!rowId) {
		console.log('CAN NOT FIND ITEM WITH ID  '+ id);
		return false;
	}
	return rowId;
}
function prepareToPlotDate(data) {
	if(typeof(data) === 'string') {
		if(data) {
			data = str_replace(data, '/', '-');
			return (new Date(data)).getTime();
		}
	}
	return data;
}
function grsInitPlugNotices() {
	var $notices = jQuery('.supsystic-admin-notice');
	if($notices && $notices.size()) {
		$notices.each(function(){
			jQuery(this).find('.notice-dismiss').click(function(){
				var $notice = jQuery(this).parents('.supsystic-admin-notice');
				if(!$notice.data('stats-sent')) {
					// User closed this message - that is his choise, let's respect this and save it's saved status
					jQuery.sendFormGrs({
						data: {mod: 'supsystic_promo', action: 'addNoticeAction', code: $notice.data('code'), choice: 'hide'}
					});
				}
			});
			jQuery(this).find('[data-statistic-code]').click(function(){
				var href = jQuery(this).attr('href')
				,	$notice = jQuery(this).parents('.supsystic-admin-notice');
				jQuery.sendFormGrs({
					data: {mod: 'supsystic_promo', action: 'addNoticeAction', code: $notice.data('code'), choice: jQuery(this).data('statistic-code')}
				});
				$notice.data('stats-sent', 1).find('.notice-dismiss').trigger('click');
				if(!href || href === '' || href === '#')
					return false;
			});
			var $enbStatsBtn = jQuery(this).find('.grsEnbStatsAdBtn');
			if($enbStatsBtn && $enbStatsBtn.size()) {
				$enbStatsBtn.click(function(){
					jQuery.sendFormGrs({
						data: {mod: 'supsystic_promo', action: 'enbStatsOpt'}
					});
					return false;
				});
			}
		});
	}
}
/**
 * Main promo popup will show each time user will try to modify PRO option with free version only
 */
function grsGetMainPromoPopup() {
	if(jQuery('#grsOptInProWnd').hasClass('ui-dialog-content')) {
		return jQuery('#grsOptInProWnd');
	}
	return jQuery('#grsOptInProWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 540
	,	height: 200
	,	open: function() {
			jQuery('#grsOptWndTemplateTxt').hide();
			jQuery('#grsOptWndOptionTxt').show();
		}
	});
}
function grsInitMainPromoPopup() {
	if(!GRS_DATA.isPro) {
		var $proOptWnd = grsGetMainPromoPopup();
		jQuery('.grsProOpt').change(function(e){
			e.stopPropagation();
			var needShow = true
			,	isRadio = jQuery(this).attr('type') == 'radio'
			,	isCheck = jQuery(this).attr('type') == 'checkbox';
			if(isRadio && !jQuery(this).attr('checked')) {
				needShow = false;
			}
			if(!needShow) {
				return;
			}
			if(isRadio) {
				jQuery('input[name="'+ jQuery(this).attr('name')+ '"]:first').parents('label:first').click();
				if(jQuery(this).parents('.iradio_minimal:first').length) {
					var self = this;
					setTimeout(function(){
						jQuery(self).parents('.iradio_minimal:first').removeClass('checked');
					}, 10);
				}
			}
			var parent = null;
			if(jQuery(this).parents('#grsPopupMainOpts').length) {
				parent = jQuery(this).parents('label:first');
			} else if(jQuery(this).parents('.grsPopupOptRow:first').length) {
				parent = jQuery(this).parents('.grsPopupOptRow:first');
			} else {
				parent = jQuery(this).parents('tr:first');
			}
			if(!parent.size()) return;
			var promoLink = parent.find('.grsProOptMiniLabel a').attr('href');
			if(promoLink && promoLink != '') {
				jQuery('#grsOptInProWnd a').attr('href', promoLink);
			}
			$proOptWnd.dialog('open');
			return false;
		});
	}
}
function grsInitConnectedOpts(form) {
	var $form = jQuery(form);
	/*Connected options: some options need to be visible  only if in other options selected special value (e.g. if send engine SMTP - show SMTP options)*/
	var $connectOpts = $form.find('[data-connect]');
	if($connectOpts && $connectOpts.size()) {
		var $connectedTo = {};
		$connectOpts.each(function(){
			var connectToData = jQuery(this).data('connect').split(':')
			,	$connectTo = $form.find('[name="opt_values['+ connectToData[ 0 ]+ ']"]')
			,	connected = $connectTo.data('connected');
			if(!connected) connected = {};
			if(!connected[ connectToData[1] ]) connected[ connectToData[1] ] = [];
			connected[ connectToData[1] ].push( this );
			$connectTo.data('connected', connected);
			if(!$connectTo.data('binded')) {
				$connectTo.change(function(){
					var connected = jQuery(this).data('connected')
					,	value = jQuery(this).val();
					// Fix for radiobuttons - oh, those radiobuttons:)
					if(jQuery(this).attr('type').toLowerCase() === 'radio' && !jQuery(this).attr('checked')) {
						return;
					}
					if(connected) {
						for(var connectVal in connected) {
							if(connected[ connectVal ] && connected[ connectVal ].length) {
								var show = connectVal == value;
								for(var i = 0; i < connected[ connectVal ].length; i++) {
									show 
									? jQuery(connected[ connectVal ][ i ]).show( g_grsAnimationSpeed ) 
									: jQuery(connected[ connectVal ][ i ]).hide( g_grsAnimationSpeed );
								}
							}
						}
					}
				});
				$connectTo.data('binded', 1);
			}
			$connectedTo[ connectToData[ 0 ] ] = $connectTo;
		});
		for(var connectedName in $connectedTo) {
			$connectedTo[ connectedName ].change();
		}
	}
}