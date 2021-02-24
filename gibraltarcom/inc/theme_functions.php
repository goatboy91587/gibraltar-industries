<?php
//Banner block in header
function get_banner_html($id){
	$banner_html = '';
	if(have_rows('banner', $id) && (is_page() || is_single())){
		$banner_html .= '<div class="align-content">';
		while(have_rows('banner', $id)){ the_row();
			$title = get_sub_field('title') ? get_sub_field('title') : get_the_title($id);
			if(get_row_layout() == 'simple'){
				$_class = (!is_page_template('pages/template-strategy.php')) ? 'w-100' : '';
				$banner_html .= '<div class="text-holder '.$_class.'">
									<h1>'.$title.'</h1>
								</div>';
			}elseif(get_row_layout() == 'collage'){
				$button = get_sub_field('link'); $link = $img = '';
				$image = get_sub_field('image_collage');
				if($image){
					$img = retina_image_html($image, '<div class="image-collage">', '</div>', '(max-width: 639px)', false);
				}
				if($button){
					$target = $button['target'] ? $button['target'] : '_self';
					$btn_title = $button['title'] ? $button['title'] : __('Learn more', 'gibraltarcom');
					$link = '<a href="'.$button['url'].'" target="'.$target.'" class="btn-cta">'.$btn_title.'</a>';
				}
				$banner_html .= '<div class="text-holder">
									<h1>'.$title.'</h1>
									'.get_sub_field('text').'
									'.$link.'
								</div>
								'.$img;
			}
			$banner_html .= '</div>';
		}
	}
	return $banner_html;
}


function blue_block(){
	if(have_rows('blue_block') && (is_page() || is_single()))
	{ 	$class = (is_page_template('pages/template-leadership.php') ||
				  is_page_template('pages/template-press-resources.php') ||
				  is_page_template('pages/template-bussiness.php')) ? '' : 'bg-light'; ?>
		<div class="modules-holder <?php echo $class ?>">
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
		</div>
	<?php
	}
}


function retina_image_html($image, $before, $after, $media, $echo = true){
	$html = '';
	if($image) {
		$img = (isset($image['img']) && !empty($image['img'])) ? $image['img'] : '';
		$img_sm = (isset($image['img_sm']) && !empty($image['img_sm'])) ? $image['img_sm'] : $img;
		$img_2x = (isset($image['img_2x']) && !empty($image['img_2x'])) ? $image['img_2x'] : $img;
		$img_2x_sm = (isset($image['img_2x_sm']) && !empty($image['img_2x_sm'])) ? $image['img_2x_sm'] : $img;
		if($img){ 
			$html .= $before.
						'<picture>
							<!--[if IE 9]><video style="display: none;"><![endif]-->
							<source srcset="'. $img_sm['url'].', '. $img_2x_sm['url'] .' 2x" media="'. $media .'">
							<source srcset="'. $img['url'].', '. $img_2x['url'].' 2x">
							<!--[if IE 9]></video><![endif]-->
							<img src="'. $img['url'].'" alt="'. $img['alt'].'">
						</picture>'
					. $after;
		}
	}
	if($echo) echo $html; else return $html;
}

/** 
 * Enables the HTTP Strict Transport Security (HSTS) header in WordPress. 
 */
function tg_enable_strict_transport_security_hsts_header_wordpress() {
    header( 'Strict-Transport-Security: max-age=10886400' );
}
add_action( 'send_headers', 'tg_enable_strict_transport_security_hsts_header_wordpress' );
