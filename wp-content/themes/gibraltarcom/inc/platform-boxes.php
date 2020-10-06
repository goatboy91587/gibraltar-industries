<?php $block_title = get_field('block_title');
$block_text = get_field('block_text');
$button = get_field('button');
if(have_rows('boxes') || $button || $block_title || $block_text){ ?>
<section class="atricles-block container bg-light bg-extended">
	<?php if($block_title || $block_text){ ?>
	<header class="section-header text-center">
		<?php if($block_title){ ?><h2><?php echo $block_title; ?></h2><?php } ?>
		<?php echo $block_text ?>
	</header>
	<?php } ?>
	<div class="posts-holder">
		<?php while(have_rows('boxes')){ the_row();
			$link = get_sub_field('link'); ?>
			<article class="article-post">
				<?php if($img = get_sub_field('image')){ ?>
					<?php retina_image_html($img, '<div class="visual">', '</div>', '(max-width: 639px)'); ?>
				<?php } ?>
				<div class="text-holder">
					<h2>
						<?php if(isset($link['url']) && !empty($link['url'])){ ?><a href="<?php echo $link['url']; ?>"><?php } ?>
							<?php the_sub_field('title') ?>
						<?php if(isset($link['url']) && !empty($link['url'])){ ?></a><?php } ?>
					</h2>
					<?php the_sub_field('text'); ?>
					<?php if(isset($link['url']) && !empty($link['url'])){
						$target = $link['target'] ? $link['target'] : '_self';
						$btn_title = $link['title'] ? $link['title'] : __('Learn more', 'gibraltarcom');
						$class = 'more' ?>
						<a href="<?php echo $link['url']; ?>" target="<?php echo $target ?>" class="<?php echo $class; ?>"><span class="icon-arrow-right"></span></a>
					<?php } ?>
				</div>
			</article>
		<?php } ?>
	</div>
	<?php if(isset($button['url']) && !empty($button['url'])){ ?>
	<div class="btn-holder text-center text-large">
		<a href="<?php echo $button['url']; ?>" class="btn-cta"><?php echo $button['name']; ?></a>
	</div>
	<?php } ?>
</section>
<?php } ?>