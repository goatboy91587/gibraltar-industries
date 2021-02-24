<div class="grsAdminFooterShell">
	<div class="grsAdminFooterCell">
		<?php echo GRS_WP_PLUGIN_NAME?>
		<?php _e('Version', GRS_LANG_CODE)?>:
		<a target="_blank" href="http://wordpress.org/plugins/gdpr-compliance-by-supsystic/changelog/"><?php echo GRS_VERSION;?></a>
	</div>
	<div class="grsAdminFooterCell">|</div>
	<?php  if(!frameGrs::_()->getModule(implode('', array('l','ic','e','ns','e')))) {?>
	<div class="grsAdminFooterCell">
		<?php _e('Go', GRS_LANG_CODE)?>&nbsp;<a target="_blank" href="<?php echo frameGrs::_()->getModule('supsystic_promo')->getMainLink();?>"><?php _e('PRO', GRS_LANG_CODE)?></a>
	</div>
	<div class="grsAdminFooterCell">|</div>
	<?php } ?>
	<div class="grsAdminFooterCell">
		<a target="_blank" href="http://wordpress.org/support/plugin/gdpr-compliance-by-supsystic"><?php _e('Support', GRS_LANG_CODE)?></a>
	</div>
	<div class="grsAdminFooterCell">|</div>
	<div class="grsAdminFooterCell">
		<?php _e('Add your', GRS_LANG_CODE)?> <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/gdpr-compliance-by-supsystic?filter=5#postform">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on wordpress.org.
	</div>
</div>