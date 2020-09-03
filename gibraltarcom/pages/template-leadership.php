<?php
/*
Template Name: Leadership Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
		<?php if(have_rows('team_groups'))
		{
			while(have_rows('team_groups'))
			{ the_row();
				$class = (get_row_index()%2==0) ? '' : 'bg-light';
				$members = get_sub_field('members');
				$popups = '';
				if($members)
				{
					$popups = '<div class="popup-holder">';
					echo '<section class="team-block container '.$class.' bg-extended">
						<div class="row">
							<div class="col heading-col">
							  <h3>'.get_sub_field('name').'</h3>
							</div>';
							foreach($members as $post)
							{ setup_postdata($post); ?>
								<div class="col team-member">
									<a href="#popup<?php the_ID();?>" class="lightbox text-small">
									  <?php if(has_post_thumbnail()){
										$img = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'full');
										$img_2x = get_field('Featured_image_2x') ? get_field('Featured_image_2x') : $img; ?>
										<div class="visual">
										   <picture>
											  <!--[if IE 9]><video style="display: none;"><![endif]-->
											  <source srcset="<?php echo $img ?>, <?php echo $img_2x ?> 2x">
											  <!--[if IE 9]></video><![endif]-->
											  <img src="<?php echo $img?>" alt="<?php the_title() ?>">
											</picture>
										</div>
									  <?php } ?>
									  <div class="text-wrap">
										<strong class="name"><?php the_title() ?></strong>
										<?php the_field('position'); ?>
									  </div>
									</a>
								  </div>
								<?php $popups .= '<div id="popup'.get_the_ID().'" class="popup bg-dark">
													'.get_the_content().'
												  </div>';
							} wp_reset_postdata();
					echo '</div>
					</section>';
					$popups .= '</div>';
					echo $popups;
				}				
			}
		} ?>
	<?php endwhile; ?>
<?php get_footer(); ?>