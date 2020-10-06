<?php if(have_rows('blue_block'))
{ ?>
	<section class="container investors-block">
    <div class="row two-cols">
				<?php while(have_rows('blue_block'))
					{ the_row();
						$link = get_sub_field('link'); ?>
						<div class="col">
							<?php if($link){
								$target = $link['target'] ? $link['target'] : '_self';		
								?><a href="<?php echo $link['url']; ?>" target="<?php echo $target ?>" class="investor-post"><?php } ?>
								<div class="text-align">
									<h2><?php the_sub_field('title') ?></h2>
									<?php the_sub_field('text'); ?>
									<?php if($link){ ?><span class="icon icon-arrow-right"></span><?php } ?>
								</div>
							<?php if($link){ ?></a><?php } ?>
						</div>						
						<?php
					} ?>					
		</div>
	</section>
	<?php
} ?>