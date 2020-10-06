<a href="#" class="button grsAddAgreement">
	<i class="fa fa-fw fa-plus"></i>
	<?php _e('Add Agreement', GRS_LANG_CODE)?>
</a>
<div id="grsAgreementsShell">
	<div class="grsAgreementShellDesc"><h3><?php _e('You can load additional scripts if user agree with your global privacy policy - just add scripts to Header or Footer here', GRS_LANG_CODE)?>:</h3></div>
	<div id="grsAgreementsGlobalShell"></div>
	<div class="grsAgreementShellDesc"><h3><?php _e('Or you can create separate conditions to agree with - and load scripts (use cookies) according to them', GRS_LANG_CODE)?>:</h3></div>
	<div id="grsAgreementsStandardShell"></div>
	<div id="grsAgreementEx" class="grsAgreement">
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<label class="supsystic-tooltip sup-no-init" title="<?php _e('By enabling this you will enable scripts output once use will agree with your Policies.', GRS_LANG_CODE)?>">
							<?php echo htmlGrs::checkbox('agreements[][enb]', array('value' => 1))?>
							<?php _e('Enable', GRS_LANG_CODE)?>
						</label>
						<a href="#" class="button grsRemoveAgreementBtn" style="float: right;"><?php _e('Remove', GRS_LANG_CODE)?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php echo htmlGrs::text('agreements[][label]', array('attrs' => 'placeholder="'. __('Label', GRS_LANG_CODE). '" class="supsystic-tooltip sup-no-init" title="'. __('Name of agreement that user will see in notification', GRS_LANG_CODE). '"'))?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php echo htmlGrs::textarea('agreements[][desc]', array('attrs' => 'placeholder="'. __('Description', GRS_LANG_CODE). '" class="supsystic-tooltip sup-no-init" title="'. __('Description of this Agreement that users will see and will be able to agree with.', GRS_LANG_CODE). '"'))?>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12"><?php echo htmlGrs::textarea('agreements[][scripts_header]', array('attrs' => 'placeholder="'. __('Scripts to Header', GRS_LANG_CODE). '" class="supsystic-tooltip sup-no-init" title="'. __('Those scripts will be output in the header of your site - before whole site content will be loaded - once user will agree with your Policies.', GRS_LANG_CODE). '"'))?></div>
				</div>
				<div class="row">
					<div class="col-sm-12"><?php echo htmlGrs::textarea('agreements[][scripts_footer]', array('attrs' => 'placeholder="'. __('Scripts to Footer', GRS_LANG_CODE). '" class="supsystic-tooltip sup-no-init" title="'. __('Those scripts will be output in the footer of your site - right after whole site content will be loaded - once user will agree with your Policies.', GRS_LANG_CODE). '"'))?></div>
				</div>
			</div>
			<?php echo htmlGrs::hidden('agreements[][is_global]', array('value' => '0'))?>
			<div style="clear: both;"></div>
		</div>
	</div>
</div>