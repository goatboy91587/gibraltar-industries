<?php $block_headline = get_field('block_headline');
$block_subhead = get_field('block_subhead');
if(have_rows('image_text_combo') || $block_headline || $block_subhead){ ?>
	<section class="bg-light image-text-combo">
		<?php if($block_headline || $block_subhead){ ?>
			<header class="section-header text-center">
				<?php if($block_headline){ ?><h2><?php echo $block_headline; ?></h2><?php } ?>
				<?php if($block_subhead){ ?><p><?php echo $block_subhead; ?></p><?php } ?>
			</header>
		<?php } ?>
		<div class="combo">
			<?php while(have_rows('image_text_combo')){ the_row();
				$copy = get_sub_field('copy'); ?>
				<article class="article-post">
					<?php if($image = get_sub_field('image')){ 
						$image_1x = $image['image_1x'];
						$image_2x = $image['image_2x'];
					?>
						<picture>
							<!--[if IE 9]><video style="display: none;"><![endif]-->
							<source srcset="<?= $image_1x['url'] .', '. $image_1x['url']?> 2x" media="(max-width: 639px)">
							<!--[if IE 9]></video><![endif]-->
							<img src="<?=$image_1x['url']?>" alt="<?=$image_1x['alt']?>">
						</picture>	
						<?php
						} ?>
						<div class="text-holder"><?php echo $copy ?></div>
				</article>
			<?php } ?>
	</section>
<?php } ?>