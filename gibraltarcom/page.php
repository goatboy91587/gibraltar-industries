<?php get_header(); ?>
	<section class="twocolumns container">
		<?php while ( have_posts() ) : the_post(); ?>
			<!--<?php the_title( '<header class="section-header"><h1>', '</h1></header>' ); ?>-->
			<div id="content">
				<?php the_post_thumbnail( 'full' ); ?>
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit', 'gibraltarcom' ) ); ?>
			</div>
		<?php endwhile; ?>
		<?php wp_link_pages(); ?>
		<?php comments_template(); ?>
		<?php get_sidebar('page'); ?>
	</section>	
<?php get_footer(); ?>