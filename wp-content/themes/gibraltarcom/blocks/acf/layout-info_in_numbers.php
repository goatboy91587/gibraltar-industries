<?php $block_title = get_sub_field('block_title');
$bg_block_image = get_sub_field('bg_block_image');
$block_text = get_sub_field('block_text');
$why_us_text = get_sub_field('why_us_text');
$why_us_image = get_sub_field('why_us_image');
if($block_title || $bg_block_image || $block_text || $why_us_image || $why_us_text || have_rows('numbers')){ ?>
<section class="numbers-block bg-dark ">
	<?php retina_image_html($bg_block_image, '<div class="bg-stretch">', '</div>', '(max-width: 639px)', $echo = true) ?>
	<div class="container">
		<?php if($block_title || $block_text){ ?>
		<div class="section-header text-center">
			<?php if($block_title){ ?><h3><?php echo $block_title; ?></h3><?php } ?>
			<?php echo $block_text; ?>
		</div>
		<?php }
		if($why_us_text || $why_us_image){ ?>
			<div class="why-us">
				<div class="grey-block"><p><?php echo $why_us_text; ?></p></div>
				<?php retina_image_html($why_us_image, '<div class="grey-block-img">', '</div>', '(max-width: 639px)', $echo = true) ?>
			</div>
		<?php }


		if(have_rows('numbers')){ ?>
		<ul class="numbers-list text-large">
			<?php while(have_rows('numbers'))
			{ the_row(); ?>
				<li>
					<?php $image_number = get_sub_field('numbers');
					$image1x = $image_number['image_number'];
					$image2x = $image_number['image_number_2x'];
					if($image_number){ ?>
					<div class="number">
					<picture>
							<!--[if IE 9]><video style="display: none;"><![endif]-->
							<source srcset="<?= $image1x['url'] .', '. $image2x['url']?> 2x" media="(max-width: 639px)">
							<!--[if IE 9]></video><![endif]-->
							<img src="<?=$image1x['url']?>" alt="<?=$image1x['alt']?>">
						</picture>
					</div>
					<?php } ?>
					<div class="text-holder">
						<?php the_sub_field('text') ?>
					</div>
				</li>
				<?php
			} ?>
		</ul>
		<?php } ?>
	</div>
</section>
<?php } ?>