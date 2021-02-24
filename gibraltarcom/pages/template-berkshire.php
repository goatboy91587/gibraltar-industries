<?php
/*
Template Name: Berkshire Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
		<section class="intro-block container">
			<?php  the_content(); ?>
		</section>
	<?php endwhile; ?>
<?php get_footer(); ?>