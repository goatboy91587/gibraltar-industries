<?php
/*
Template Name: Strategy Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
		<?php include( get_template_directory() . '/inc/black-box.php' ); ?>
		<?php include( get_template_directory() . '/inc/platform-horizontal-tile.php' ); ?>
	<?php endwhile; ?>
<?php get_footer(); ?>