jQuery(document).ready(function(){
	jQuery('#ppsSettingsSaveBtn').click(function(){
		jQuery('#ppsSettingsForm').submit();
		return false;
	});
	jQuery('#ppsSettingsForm').submit(function(){
		jQuery(this).sendFormGrs({
			btn: jQuery('#ppsSettingsSaveBtn')
		});
		return false;
	});
	grsInitConnectedOpts('#ppsSettingsForm');
});