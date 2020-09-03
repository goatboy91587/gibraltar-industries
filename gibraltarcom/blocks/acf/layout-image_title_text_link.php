<?php $block_title = get_sub_field('title');
$block_image = get_sub_field('image');
$block_text = get_sub_field('text');
$block_link = get_sub_field('link');
if($block_title || $block_image || $block_text || $block_link){ ?>
<section class="container career-block bg-gray bg-extended">
	<?php if($block_title || $block_text || $block_link){ ?>
	<div class="text-holder">
		<?php if($block_title){ ?><h2><?php echo $block_title; ?></h2><?php } ?>
		<?php echo $block_text; ?>
		<?php if($block_link){
			$target = $block_link['target'] ? $block_link['target'] : '_self';
			$btn_title = $block_link['title'] ? $block_link['title'] : __('Learn more', 'gibraltarcom');?>
			<a href="<?php echo $block_link['url']; ?>" target="<?php echo $target ?>" class="btn-cta"><?php echo $btn_title; ?></a>
		<?php } ?>
	</div>
	<?php } ?>
	<?php retina_image_html($block_image, '<div class="visual">', '</div>', '(max-width: 639px)', $echo = true) ?>
</section>
<?php } ?>