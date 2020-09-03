<?php
/*
Template Name: Strategy Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
	    <?php if (!is_page (array('about-us'))) { ?>
			<?php include( get_template_directory() . '/inc/black-box.php' ); ?>
			<?php include( get_template_directory() . '/inc/platform-horizontal-tile.php' ); ?>
		<?php } ?>
		<?php if(have_rows('blocks')){
			while(have_rows('blocks')){ the_row();
				get_template_part('blocks/acf/layout', get_row_layout());
			}
		}?>
	<?php endwhile; ?>
<?php get_footer(); ?>