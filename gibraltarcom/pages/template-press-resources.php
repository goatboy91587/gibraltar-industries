<?php
/*
Template Name: Press/Contact Template
*/
get_header(); ?>
	<?php while ( have_posts( ) ) : the_post(); ?>
	<section class="intro-block contact-block container">
		<header class="text-large contact-header max-70">
			<?php the_content() ?>
		</header>
		<?php if(have_rows('blocks')){
			while(have_rows('blocks')){ the_row();
				get_template_part('blocks/acf/layout', get_row_layout());
			}
		} ?>
	</section>
	<?php endwhile; ?>
<?php get_footer(); ?>