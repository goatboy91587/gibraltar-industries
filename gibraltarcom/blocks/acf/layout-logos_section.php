<div class="content-block">
	<?php if($bl_title = get_sub_field('block_title')){ ?>
	<div class="section-header">
		<h2><?php echo $bl_title ?></h2>
	</div>
	<?php } ?>
	<div class="content-holder">
		<?php if(get_sub_field('add_text') && $text = get_sub_field('text')) echo $text ?>
		<?php if(have_rows('logos'))
		{ ?>
			<div class="row two-cols">
				<?php while(have_rows('logos'))
				{ the_row(); ?>
					<div class="col">
						<div class="logo-frame">
							<?php if($logo = get_sub_field('logo_image')){ ?>
							<div class="visual">
								<span class="align">
									<img src="<?php echo $logo['url']?>" width="254" height="123" alt="<?php echo $logo['alt']?>">
								</span>
							</div>
							<?php }
							$logo_name = get_sub_field('logo_name');
							$file_to_download = get_sub_field('file_to_download');
							$file_name = get_sub_field('file_name');
							$size = get_sub_field('size');
							if($logo_name || $file_to_download || $file_name || $size){ ?>
							<div class="text-holder">
								<div class="logo-meta text-small">
									<?php if($logo_name){ ?><span class="color d-block"><?php echo esc_html($logo_name) ?></span><?php } ?>
									<?php if($file_name){ ?><span class="name d-block"><?php echo esc_html($file_name) ?></span><?php } ?>
									<?php if($size){ ?><span class="size d-block"><?php echo $size ?> Kb</span><?php } ?>
								</div>
								<?php if($file_to_download){ ?>
								<div class="download-link">
									<a href="<?php echo $file_to_download ?>" download><?php _e('Download', 'gibraltarcom'); ?></a>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php
				} ?>			
			</div>
			<?php
		} ?>
	</div>
</div>