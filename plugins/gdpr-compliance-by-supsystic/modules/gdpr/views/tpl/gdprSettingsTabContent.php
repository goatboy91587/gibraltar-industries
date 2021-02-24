<div id="grsSettingsTabs">
	<section class="supsystic-bar">
		<h3 class="nav-tab-wrapper grsMainTabsNav" style="margin-bottom: 0px; margin-top: 12px;">
			<?php $i = 0;?>
			<?php foreach($this->tabs as $tKey => $tData) { ?>
				<?php
					$iconClass = 'grs-edit-icon';
					if(isset($tData['avoid_hide_icon']) && $tData['avoid_hide_icon']) {
						$iconClass .= '-not-hide';	// We will just exclude it from selector to hide, jQuery.not() - make browser slow down in this case - so better don't use it
					}
				?>
				<a class="nav-tab <?php if($i == 0) { echo 'nav-tab-active'; }?>" href="#<?php echo $tKey?>">
					<?php if(isset($tData['fa_icon'])) { ?>
						<i class="<?php echo $iconClass?> fa <?php echo $tData['fa_icon']?>"></i>
					<?php } elseif(isset($tData['icon_content'])) { ?>
						<i class="<?php echo $iconClass?> fa"><?php echo $tData['icon_content']?></i>
					<?php }?>
					<span class="grsPopupTabTitle"><?php echo $tData['title']?></span>
				</a>
			<?php $i++; }?>
		</h3>
	</section>
	<section>
		<div class="supsystic-item supsystic-panel" style="padding-left: 10px;">
			<div id="containerWrapper">
				<form id="grsSettingsForm">
					<?php foreach($this->tabs as $tKey => $tData) { ?>
						<div id="<?php echo $tKey?>" class="grsTabContent">
							<?php echo $tData['content']?>
						</div>
					<?php }?>
					<?php echo htmlGrs::hidden('mod', array('value' => 'gdpr'))?>
					<?php echo htmlGrs::hidden('action', array('value' => 'save'))?>
					<?php echo htmlGrs::nonceForAction('save')?>
				</form>
				<div style="clear: both;"></div>
			</div>
		</div>
	</section>
</div>