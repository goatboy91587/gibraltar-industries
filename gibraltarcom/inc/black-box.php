<?php if(have_rows('black_block')){ ?>
	<section class="intro-block container">
		<?php while(have_rows('black_block')){ the_row();  ?>
		<div class="content-block strat">
			<?php if($title = get_sub_field('title')){ ?>
			<div class="section-header">
				<h2><?php echo $title; ?></h2>
			</div>
			<?php } ?>


			<?php if($title_subtext = get_sub_field('title_subtext')){ ?>
			<div class="content-holder strat">
				<?php echo $title_subtext; ?>
			</div>
			<?php } ?>
			
			<?php $sm_text = get_sub_field('small_text');
			if(have_rows('blocks') || $sm_text){ ?>
				<div class="content-holder">
					<?php while(have_rows('blocks')){ the_row(); ?>
					
						<div class="sub-content">
						
							<?php
								$icon = get_sub_field('icon');
							
							if(!empty($icon)): 
								$icon_1x = $icon['icon_1x'];
								$icon_2x = $icon['icon_2x'];
							?>
							<div class="icon">
							<picture>
								<!--[if IE 9]><video style="display: none;"><![endif]-->
								<source srcset="<?= $icon_1x['url'] .', '. $icon_2x['url']?> 2x" media="(max-width: 639px)">
								<!--[if IE 9]></video><![endif]-->
								<img src="<?=$icon_1x['url']?>" alt="<?=$icon_1x['alt']?>">
							</picture>
							<hr>
							</div>
								
								<?php
							endif; ?> 
						


						<?php if($_title = get_sub_field('title')){ ?>
							<h3><?php echo $_title ?></h3>
						<?php }
						the_sub_field('text');
						
						?>
				
						<?php
							$bars_desktop = get_sub_field('bars_desktop');
							
							if(!empty($bars_desktop)):
								foreach($bars_desktop as $bar):
									 $bars_desktop_1x = $bar['bars_desktop_1x'];
									 $bars_desktop_2x = $bar['bars_desktop_2x']; ?>

									<div class="bar-desktop">
										<picture>
										<!--[if IE 9]><video style="display: none;"><![endif]-->
										<source srcset="<?= $bars_desktop_1x['url'] .', '. $bars_desktop_2x['url']?> 2x" media="(max-width: 639px)">
										<!--[if IE 9]></video><![endif]-->
										<img src="<?=$bars_desktop_1x['url']?>" alt="<?=$bars_desktop_1x['alt']?>">
										</picture>	
									</div>
								<?php	endforeach; 
							endif; 
							
							$bars_mobile = get_sub_field('bars_mobile');

							if(!empty($bars_mobile)):

								foreach($bars_mobile as $bar):
									$bars_mobile_1x = $bar['bars_mobile_1x'];
									$bars_mobile_2x = $bar['bars_mobile_2x']; ?>
								<div class="bar-mobile">
									<picture>
									<!--[if IE 9]><video style="display: none;"><![endif]-->
									<source srcset="<?= $bars_mobile_1x['url'] .', '. $bars_mobile_2x['url']?> 2x" media="(max-width: 639px)">
									<!--[if IE 9]></video><![endif]-->
									<img src="<?=$bars_mobile_1x['url']?>" alt="<?=$bars_mobile_1x['alt']?>">
									</picture>
								</div>	
								<?php endforeach;
							endif;
							
							?>

						</div>

					<?php } ?>
					
					<?php if($sm_text){ ?>
					<hr class="sm-text-hr">
					<div class="sources-details text-small">
						<?php echo $sm_text; ?>
					</div>
					<hr class="sm-text-hr">
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php } ?>
	</section>
<?php } ?>