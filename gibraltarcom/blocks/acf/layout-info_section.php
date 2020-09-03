<div class="content-block <?php if(get_sub_field('background_color')) echo ' bg-light'; ?>">
	<?php if($bl_title = get_sub_field('block_title')){ ?>
	<div class="section-header">
		<h2><?php echo $bl_title ?></h2>
	</div>
	<?php } ?>
	<div class="content-holder">
		<?php if(get_sub_field('add_text') && $text = get_sub_field('text')) echo $text;
		$info = get_sub_field('info');
		if(get_sub_field('add_info') && !empty($info)){ ?>
		<address class="address">
			<?php if(isset($info['big_title']) && !empty($info['big_title'])){ ?><strong class="title text-large d-block"><?php echo $info['big_title']; ?></strong><?php }
			$html = array();
			if(isset($info['info']) && !empty($info['info'])){
				foreach($info['info'] as $key=>$value){
					if($value['acf_fc_layout'] == 'address'){
						$html['address'] = $value['value'];
					}elseif($value['acf_fc_layout'] == 'small_text'){
						$html['small_text'] = $value['value'];
					}elseif($value['acf_fc_layout'] == 'email'){
						$html['email'] = '<a href="mailto:'.antispambot($value['value']).'">'.antispambot($value['value']).'</a>';
					}elseif($value['acf_fc_layout'] == 'phone'){
						$html['phone'] = '';
						if(isset($value['name']) && !empty($value['name'])) $html['phone'] .= $value['name'] .' ';
						$html['phone'] .= '<a href="tel:'.clean_phone($value['value']).'">'.$value['value'].'</a>';
						if(isset($value['description']) && !empty($value['description'])) $html['phone'] .= ' '.$value['description'];
					}
				}
				echo implode('<br>', $html);
			}
			?>
		</address>
		<?php } ?>
	</div>
</div>