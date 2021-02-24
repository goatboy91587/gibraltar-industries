jQuery(document).ready(function(){
	
	var agreementsNavigate = {
		_$mainShell: jQuery('#grsAgreementsShell')
	,	_$globalShell: jQuery('#grsAgreementsGlobalShell')
	,	_$standardShell: jQuery('#grsAgreementsStandardShell')
	,	_$exRow: jQuery('#grsAgreementEx')
	,	_fields: {
			'enb': {}
		,	'label': {}
		,	'desc': {}
		,	'scripts_header': {}
		,	'scripts_footer': {}
		,	'is_global': {}
	}
	,	init: function() {
			grsCheckDestroy(this._$exRow.find('input[type="checkbox"]'));
			this._$exRow.find(':input').attr('disabled', 'disabled');
	}
	,	add: function(data) {
			var $row = this._$exRow.clone().removeAttr('id')
			,	isGlobal = false;
			if(data) {
				for(var k in this._fields) {
					var $input = $row.find('[name*="'+ k+ '"]');
					if($input && $input.length) {
						switch($input.tagName().toLowerCase()) {
							case 'input':
								switch($input.attr('type').toLowerCase()) {
									case 'checkbox':
										data[ k ] ? $input.attr('checked', 'checked') : $input.removeAttr('checked');
										break;
									default:
										$input.val(data[ k ]);
										break;
								}
								break;
							case 'textarea':
								$input.val(data[ k ]);
								break;
						}
					}
				}
				if(parseInt(data['is_global'])) {
					isGlobal = true;
				}
			}
			if(isGlobal) {
				this._$globalShell.append($row);
			} else {
				this._$standardShell.append($row);
			}
			$row.find(':input').removeAttr('disabled');
			this._reorder();
			grsInitCustomCheckRadio( $row );
			$row.find('.sup-no-init').removeClass('sup-no-init');
			grsInitTooltips( $row );
			$row.find('.grsRemoveAgreementBtn').click(function(){
				agreementsNavigate.remove( this );
				return false;
			});
			return $row;
			
	},	remove: function( btn ) {
			jQuery(btn).parents('.grsAgreement:first').remove();
			this._reorder();
	},	_reorder: function() {
			var $rows = this._$mainShell.find('.grsAgreement');
			if($rows && $rows.length > 0) {
				var i = 0;
				$rows.each(function(){
					var $inputs = jQuery(this).find(':input');
					$inputs.each(function(){
						var name = jQuery(this).attr('name');
						jQuery(this).attr('name', name.replace(/(agreements\[\]|agreements\[\d+\])/g, 'agreements['+ i+ ']'));
					});
					i++;
				});
			}
	}
		
	};
	jQuery('.grsAddAgreement').click(function(){
		var $row = agreementsNavigate.add();
		jQuery('html, body').animate({
			scrollTop: $row.offset().top
		}, 700);
		return false;
	});
	agreementsNavigate.init();
	if(typeof(grsAgreements) !== 'undefined') {
		for(var i = 0; i < grsAgreements.length; i++) {
			agreementsNavigate.add( grsAgreements[ i ] );
		}
	}
});