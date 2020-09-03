<?php
/*
Template Name: Social Responsibility
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
		<?php include( get_template_directory() . '/inc/image-text-combo.php' ); ?>
		<?php include( get_template_directory() . '/inc/platform-horizontal-tile.php' ); ?>
		<?php include( get_template_directory() . '/inc/black-box.php' ); ?>
	<?php endwhile; ?>
<?php get_footer(); ?>