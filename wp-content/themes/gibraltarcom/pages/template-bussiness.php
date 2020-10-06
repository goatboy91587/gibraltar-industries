<?php
/*
Template Name: Bussiness Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
	<section class="portfolio-block container">
		<header class="section-header text-large">
			<?php the_content() ?>
		</header>
		<?php include( get_template_directory() . '/inc/open-close-box.php' ); ?>
	</section>
	<?php endwhile; ?>
<?php get_footer(); ?>