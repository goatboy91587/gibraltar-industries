<?php $block_title = get_field('block_title');
$block_text = get_field('block_text');
$button = get_field('button');
if(have_rows('boxes_horizontal') || $button || $block_title || $block_text){ ?>
<section class="atricles-block bg-light bg-extended horiztonal social-tiles">
	<?php if($block_title || $block_text && !is_page('social-responsibility')){ ?>
	<header class="section-header text-center">
		<?php if($block_title){ ?><h2><?php echo $block_title; ?></h2><?php } ?>
		<?php echo $block_text ?>
	</header>
	<?php } ?>
	<div class="posts-holder horizontal-tile">
		<?php while(have_rows('boxes_horizontal')){ the_row();
			$link = get_sub_field('link'); ?>
			<div class="horizontal-post">
				
				<?php if($img = get_sub_field('image')){ ?>
					<?php retina_image_html($img, '<div class="visual hoverArea">', '</div>', '(max-width: 639px)'); ?>
				<?php } ?>
				<div class="text-holder">
					<h2>
						<?php the_sub_field('title') ?>
					</h2>
					<div class="overlay">
						<?php the_sub_field('text'); ?>

						<?php if(isset($link['url']) && !empty($link['url'])){
							$target = $link['target'] ? $link['target'] : '_self';
							$btn_title = $link['title'] ? $link['title'] : __('Learn more', 'gibraltarcom');
							$class = (is_page_template('pages/template-strategy.php')) ? 'more' : 'more visible-xs'; ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $target ?>" class="<?php echo $class; ?>"><span class="icon-arrow-right"></span></a>
						<?php } ?>
						</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php if(isset($button['url']) && !empty($button['url'])){ ?>
	<div class="btn-holder text-center text-large">
		<a href="<?php echo $button['url']; ?>" class="btn-cta"><?php echo $button['name']; ?></a>
	</div>
	<?php } ?>
</section>
<?php } ?>