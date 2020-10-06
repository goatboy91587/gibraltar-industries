<?php
/*
Template Name: Investors Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
		<section class="intro-block container">
      <div class="content-block">
				<div class="section-header text-large">
					<?php the_field('intro_field'); ?>
				</div>
				<div class="content-holder">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
		<?php include( get_template_directory() . '/inc/blue-block-investors.php' ); ?>
	<?php endwhile; ?>
<?php get_footer(); ?>