<?php while(have_rows('diagram')){ the_row();
	$diagram_text = get_sub_field('diagram_text'); ?>
<section class="diagram-block">
	<div class="container">
		<div class="desktop-diagram">
			<?php if($diagram_image = get_sub_field('diagram_image')){ 
					$diagram_image_1x = $diagram_image['diagram_image_1x'];
					$diagram_image_2x = $diagram_image['diagram_image_2x'];
			?>
			<picture>
				<!--[if IE 9]><video style="display: none;"><![endif]-->
				<source srcset="<?= $diagram_image_1x['url'] .', '. $diagram_image_2x['url']?> 2x" media="(max-width: 639px)">
				<!--[if IE 9]></video><![endif]-->
				<img src="<?=$diagram_image_1x['url']?>" alt="<?=$diagram_image_1x['alt']?>">
			</picture>	
				<?php
			} ?>
		</div>

		<div class="mobile-diagram">
			<?php if($diagram_image_mobile = get_sub_field('diagram_image_mobile')){ 
					$diagram_image_mobile_1x = $diagram_image_mobile['diagram_image_mobile_1x'];
					$diagram_image_mobile_2x = $diagram_image_mobile['diagram_image_mobile_2x'];
			?>
			<picture>
				<!--[if IE 9]><video style="display: none;"><![endif]-->
				<source srcset="<?= $diagram_image_mobile_1x['url'] .', '. $diagram_image_mobile_2x['url']?> 2x" media="(max-width: 639px)">
				<!--[if IE 9]></video><![endif]-->
				<img src="<?=$diagram_image_mobile_1x['url']?>" alt="<?=$diagram_image_mobile_1x['alt']?>">
			</picture>	
				<?php
			} ?>
		</div>

		<div class="text-holder">
				<div class="text-content"><?php echo $diagram_text ?></div>
		</div>
	</div>
</section>
<?php } ?>

<?php if( get_field('video_content') ): ?>
	<section class="video-block">
		<div class="container">
			<article class="article-post">
				<?php the_field('video_content'); ?>
			</article>
		</div>
	</section>
<?php endif; ?>