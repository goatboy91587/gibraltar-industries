jQuery(document).ready(function(){
	if(typeof(grsNotifyData) === 'undefined') {
		console.log('Empty data from server');
		return;
	}
	function setCookieGrs(c_name, value, expire) {
		var exdate = new Date();
		if(typeof(expire) !== 'undefined') {
			if(expire.days) {
				exdate.setDate(exdate.getDate() + expire.days);
			} else if(expire.years) {
				exdate.setFullYear(exdate.getFullYear() + expire.years);
			}
		}
		var value_prepared = '';
		if(typeof(value) == 'array' || typeof(value) == 'object') {
			value_prepared = '_JSON:'+ JSON.stringify( value );
		} else {
			value_prepared = value;
		}
		var c_value = escape(value_prepared)+ ((typeof(expire) == 'undefined') ? "" : "; expires="+exdate.toUTCString())+ '; path=/';
		document.cookie = c_name+ "="+ c_value;
	}

	function getCookieGrs(name) {
		var parts = document.cookie.split(name + "=");
		if (parts.length == 2) {
			var value = unescape(parts.pop().split(";").shift());
			if(value.indexOf('_JSON:') === 0) {
				value = JSON.parse(value.split("_JSON:").pop());
			}
			return value;
		}
		return null;
	}

	function delCookieGrs( name ) {
		document.cookie = name + '=; Max-Age=0; path=/';
	}
	
	function getAllCookieGrs() {
		var pairs = document.cookie.split(";");
		var cookies = {};
		for (var i=0; i<pairs.length; i++){
			var pair = pairs[i].split("=");
			cookies[(pair[0]+'').trim()] = unescape(pair[1]);
		}
		return cookies;
	}

	function grsNotify(data) {
		this._data = data;
		this._$ = null;
		this._$againTab = null;
		this._$bg = null;
		this._$agrees = null;
		this._visible = false;
		this._activeClass = 'grsActive';
		this._cookie = 'grs_gdpr';
		this._animSpeed = 500;
		this._totalAgrees = [];
		// r - Rejected, aa - Accepted All, a - Accepted (list of accepted)
		this._deside = {r: 0, aa: 0, a: []};
		this._firstTimeRun = false;
		this._init();
	}
	grsNotify.prototype._init = function() {
		jQuery('body').append( this._data.rendered_html );
		// Let it be for now - let's count that there are only one such
		this._$ = jQuery('#grsNotifyShell');
		this._initDeside();
		this._initAgrees();
		if(this.getOpt('main', 'enb_show_again_tab')) {
			this._initAgainTab();
		}
		if(this.getOpt('design', 'show_as') == 'popup') {
			this._initBg();
		}
		
		this._initButtons();
		if(this.getOpt('main', 'bar_delay_hide')) {
			var self = this;
			setTimeout(function(){
				self.hide();
			}, this.getOpt('main', 'bar_delay_hide_time'));
		}
	};
	grsNotify.prototype._initAgrees = function() {
		this._$agrees = this._$.find('.grsAgrees');
		if(this._$agrees && this._$agrees.length > 0) {
			var self = this;
			this._$agrees.find('input[data-hash]').each(function(){
				var hash = jQuery(this).data('hash');
				self._totalAgrees.push(hash);
				if(self._isAgree(hash)) {
					jQuery(this).attr('checked', 'checked');
				}
			});
		} else {
			this._$agrees = null;
		}
	};
	grsNotify.prototype._initBg = function() {
		var self = this;
		this._$bg = jQuery('#grsNotifyBg');
		this._$bg.click(function(){
			self.hide();
		});
	};
	grsNotify.prototype._initAgainTab = function() {
		this._$againTab = jQuery('#grsShowAgainShell');
		var self = this;
		this._$againTab.click(function(){
			self.show();
		});
	};
	grsNotify.prototype._initButtons = function() {
		for(var i = 0; i < this._data.btns.length; i++) {
			if(this.getOpt('btns', this._data.btns[ i ]+ '_enb')) {
				var self = this;
				this._$.find('#grsBtn_'+ self._data.btns[ i ]).data('grs_btn', self._data.btns[ i ]).click(function(){
					switch(jQuery(this).data('grs_btn')) {
						case 'accept_all':
							self.acceptAll();
							self.hide();
							return false;
						case 'save_decision':
							self.acceptChecked();
							self.hide();
							return false;
						case 'reject':
							self.reject();
							self.hide();
							return false;
						case 'terms':
							// Just go to terms
							break;
					}
				});
			}
		}
	};
	grsNotify.prototype._isAgree = function( hash ) {
		return this._deside.a && this._deside.a.indexOf(hash) !== -1 ? true : false;
	};
	grsNotify.prototype._initDeside = function() {
		var saved = getCookieGrs( this._cookie );
		if(saved) {
			this._deside = saved;
		} else {
			this._firstTimeRun = true;
			this._save();
		}
	};
	grsNotify.prototype.reject = function() {
		this._deside.r = 1;
		this._save();
		// Check for Block
		this.checkBlock();
	};
	grsNotify.prototype.acceptAll = function() {
		this._deside.aa = 1;
		this._save();
	};
	grsNotify.prototype.acceptChecked = function() {
		if(this._$agrees) {
			var self = this
			,	acceptedAll = true;
			this._deside.a = [];
			
			this._$agrees.find('input[data-hash]').each(function(){
				if(jQuery(this).attr('checked')) {
					self._deside.a.push(jQuery(this).data('hash'));
				} else
					acceptedAll = false;	// it have at least one non-accepted
			});
			if(acceptedAll) {
				this._deside.aa = 1;
			}
		} else
			this._deside.aa = 1;
		this._save();
	};
	grsNotify.prototype._save = function() {
		setCookieGrs(this._cookie, this._deside, {years: 1});
	};
	grsNotify.prototype.getOpt = function(category, key) {
		return this._data['opts'][ category ][ key ];
	};
	grsNotify.prototype.show = function() {
		if(!this._visible) {
			//this._$.slideUp( this._animSpeed );
			this._animateShow( this._$ );
			this._visible = true;
			this._checkHideAgainTab();
			if(this._$bg) {
				this._$bg.addClass( this._activeClass );
			}
		}
	};
	grsNotify.prototype.hide = function() {
		if(this._visible) {
			this._animateHide( this._$ );
			this._visible = false;
			this._checkShowAgainTab();
			if(this._$bg) {
				this._$bg.removeClass( this._activeClass );
			}
		}
	};
	grsNotify.prototype._animateShow = function($to) {
		$to.addClass( this._activeClass );
		switch(this.getOpt('design', 'animation')) {
			case 'slide':
				$to.hide().slideDown( this._animSpeed );
				break;
			case 'fade':
				$to.hide().fadeIn( this._animSpeed );
				break;
		}
	};
	grsNotify.prototype._animateHide = function($to) {
		$to.removeClass( this._activeClass );
		switch(this.getOpt('design', 'animation')) {
			case 'slide':
				$to.slideUp( this._animSpeed );
				break;
			case 'fade':
				$to.fadeOut( this._animSpeed );
				break;
		}
	};
	grsNotify.prototype.accepted = function() {
		return (this._deside.aa) ? true : false;
	};
	grsNotify.prototype._checkShowAgainTab = function() {
		if(this.getOpt('main', 'enb_show_again_tab') && !this.accepted()) {
			this._showAgainTab();
		}
	};
	grsNotify.prototype._checkHideAgainTab = function() {
		if(this.getOpt('main', 'enb_show_again_tab')) {
			this._hideAgainTab();
		}
	};
	grsNotify.prototype._showAgainTab = function() {
		this._animateShow( this._$againTab );
	};
	grsNotify.prototype.checkShow = function() {
		if(this._deside.r) {	// Rejected - show only tab
			this._checkShowAgainTab();
		} else {
			if(!this._deside.aa) {
				this.show();
			}
		}
	};
	grsNotify.prototype._hideAgainTab = function() {
		this._animateHide( this._$againTab );
	};
	grsNotify.prototype.checkBlock = function() {
		if(parseInt(this.getOpt('main', 'enb_block_content'))) {
			if(this._deside.r || (!this._firstTimeRun && parseInt(this.getOpt('main', 'enb_block_without_agree')))) {
				var timeoutMap = [100, 500, 3 * 60 * 1000];
				if(parseInt(this.getOpt('main', 'enb_block_resources'))) {
					this._blockContent();
					var self = this;
					for(var i = 0; i < timeoutMap.length; i++) {
						setTimeout(function(){
							self._blockContent();
						}, timeoutMap[ i ]);
					}
				}
				if(parseInt(this.getOpt('main', 'enb_block_cookie'))) {
					this._clearUnapprovedCookies();
					var self = this;
					for(var i = 0; i < timeoutMap.length; i++) {
						setTimeout(function(){
							self._clearUnapprovedCookies();
						}, timeoutMap[ i ]);
					}
				}
			}
		}
	};
	grsNotify.prototype._blockContent = function() {
		var remove = [
			{tag: 'script', attr: 'src'}
		,	{tag: 'img', attr: 'src'}
		,	{tag: 'link', attr: 'href'}
		,	{tag: 'iframe', attr: 'src'}
		]
		,	domain = getDomainGrs(window.location.href);
		
		for(var i = 0; i < remove.length; i++) {
			var $collection = jQuery(remove[ i ].tag);
			if($collection && $collection.length) {
				$collection.each(function(){
					var checkAttr = jQuery(this).attr(remove[ i ].attr);
					if(checkAttr && checkAttr.toLowerCase().indexOf(domain) === -1) {
						jQuery(this).remove();
					}
				});
			}
		}
	};
	grsNotify.prototype._clearUnapprovedCookies = function() {
		var allCookies = getAllCookieGrs()
		,	allowed = ['grs_gdpr', 'wordpress_test_cookie']
		,	allowedBeginFrom = ['wordpress_logged_in_', 'wordpress_', 'wp-settings', 'wp-settings-time'];
		if(allCookies) {
			for(var cKey in allCookies) {
				if(jQuery.inArray(cKey, allowed) !== -1) continue;
				var allowBegin = false;
				for(var k = 0; k < allowedBeginFrom.length; k++) {
					if(cKey.indexOf(allowedBeginFrom[ k ]) === 0) {
						allowBegin = true;
						break;
					}
				}
				if(allowBegin) continue;
				delCookieGrs(cKey);
			}
		}
	};
	function getDomainGrs(url) {
		var urlParts = url.replace('http://','').replace('https://','').replace('www.','').split(/[/?#]/);
		return urlParts[0];
	}
	var mainNotify = new grsNotify(grsNotifyData);
	// Check block content - very, very dangerous option!
	mainNotify.checkBlock();
	// Show it right now
	mainNotify.checkShow();
});