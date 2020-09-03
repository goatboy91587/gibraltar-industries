<?php
/*
Template Name: Home Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
		<?php if(have_rows('blocks')){
			while(have_rows('blocks')){ the_row();
				get_template_part('blocks/acf/layout', get_row_layout());
			}
		}?>
	<?php endwhile; ?>
<?php get_footer(); ?>