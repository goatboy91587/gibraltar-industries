<?php $_block_title = get_sub_field('platform_list_block_title');
if(have_rows('platform_list') || have_rows('blue_block'))
{  ?>
	<div class="modules-holder">
		<?php 
		if(have_rows('platform_list'))
		{ ?>
			<section class="platforms-block container">
				<div class="text-block">
					<?php if($_block_title){ ?><h2><?php echo $_block_title ?></h2><?php } ?>
					<ul class="platform-list">
						<?php $i=0; while(have_rows('platform_list'))
						{ the_row(); ?>
							<li>
								<a href="<?php the_sub_field('link') ?>" class="platform-option <?php if($i==0) echo 'active' ?>">
									<?php if($img = get_sub_field('image')){ ?>
									<div class="visual">
										<?php retina_image_html($img, '<div class="bg-stretch">', '</div>', '(max-width: 991px)', $echo = true) ?>
									</div>
									<?php } ?>
									<div class="text-wrap">
										<?php if($title = get_sub_field('title')){ ?><h3><?php echo $title ?></h3><?php } ?>
										<?php the_sub_field('text') ?>
										<span class="icon icon-arrow-right"></span>
									</div>
								</a>
							</li>
							<?php $i++;
						} ?>
					</ul>
				</div>
			</section>
			<?php
		}
		if(have_rows('blue_block'))
		{ ?>
			<section class="info-block container bg-dark">
				<div class="row">
					<?php while(have_rows('blue_block'))
						{ the_row();
							$link = get_sub_field('link'); ?>
							<article class="info-col col">
								<div class="text-holder">
									<h3>
										<?php if($link){ ?><a href="<?php echo $link['url']; ?>"><?php } ?>
											<?php the_sub_field('title') ?>
										<?php if($link){ ?></a><?php } ?>
									</h3>
									<?php the_sub_field('text'); ?>
								</div>
								<?php if($link){
									$target = $link['target'] ? $link['target'] : '_self';
									$btn_title = $link['title'] ? $link['title'] : __('Learn more', 'gibraltarcom'); ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $target ?>" class="btn-cta"><?php echo $btn_title; ?></a>
								<?php } ?>
							</article>
							<?php
						} ?>					
				</div>
			</section>
		<?php
		} ?>
	</div>
	<?php
} ?>