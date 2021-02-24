jQuery(document).ready(function(){
	jQuery('#grsSettingsTabs').wpTabs({
		uniqId: 'grsSettingsTabs'
	});
	jQuery('.grsSettingsSaveBtn').click(function(){
		jQuery('#grsSettingsForm').submit();
		return false;
	});
	
	jQuery('#grsSettingsForm').submit(function(){
		var addData = {};
		if(typeof(grsRichEditNames) !== 'undefined') {
			for(var i = 0; i < grsRichEditNames.length; i++) {
				var textId = 'opt_values'+ grsRichEditNames[ i ]
				,	sendValKey = 'opt_values_txt_val'+ grsRichEditNames[ i ];
				addData[ sendValKey ] = encodeURIComponent( grsGetTxtEditorVal( textId ) );
			}
		}
		jQuery(this).sendFormGrs({
			btn: jQuery('.grsSettingsSaveBtn')
		,	appendData: addData
		});
		return false;
	});
	grsInitConnectedOpts('#grsSettingsForm');
});