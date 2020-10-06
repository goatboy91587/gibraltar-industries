<?php if(have_rows('open_close_blocks')){ ?>
<ul class="portfolio-details accordion">
	<?php while(have_rows('open_close_blocks'))
	{ the_row(); ?>
		<li <?php //if(get_row_index() == 1) echo 'class="active"'; ?>>		
			<a href="#" class="opener"><?php the_sub_field('title') ?> <span class="icon-wrap bg-dark"><span class="plus"></span></span></a>
			<?php if(have_rows('open_block'))
			{ ?>
				<div class="slide">
					<?php while(have_rows('open_block'))
					{ the_row(); ?>
						<?php if($block_title = get_sub_field('block_title')){ ?><h3><?php echo $block_title; ?></h3><?php } ?>
						<div class="row">
							<?php if(have_rows('cards')){
								while(have_rows('cards'))
								{ the_row(); ?>						
									<section class="col portfolio-card">
										<?php $link = get_sub_field('link');
										$_title = get_sub_field('title');
										$web_site = get_sub_field('web_site_address');
										$flag = get_sub_field('add_companies'); ?>
										<?php if($link && $_title){ ?>
											<a href="<?php echo $link ?>" target="_blank" class="business-name"><?php echo $_title ?> <span class="icon-link"><img src="<?php echo get_template_directory_uri(); ?>/images/external-link.svg" width="18" height="18" alt="link"></span></a>
										<?php }else{
											echo '<span class="business-name">'.$_title.'</span>';
										}?>
										<?php the_sub_field('description') ?>
										<?php if($link && $web_site){ ?><a href="<?php echo $link ?>" target="_blank" class="text-small link"><?php echo $web_site ?></a><?php } ?>
										<?php if($flag && have_rows('companies')){ ?>
											<ul class="subcompanies text-small">
												<?php while(have_rows('companies')){ the_row(); ?>
												<li>
													<a href="<?php the_sub_field('link') ?>" target="_blank" class="subname"><?php the_sub_field('name') ?></a>
													<?php the_sub_field('description') ?>
												</li>
												<?php } ?>
											</ul>
										<?php } ?>
									</section>
									<?php
								}
							} ?>				
						</div>
						<?php
					} ?>
				</div>
				<?php
			} ?>
		</li>
		<?php
	} ?>
</ul>
<?php } ?>