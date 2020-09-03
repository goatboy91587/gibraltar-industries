<?php get_header(); ?>
<section class="container twocolumns">
	<div id="content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'blocks/content', get_post_type() ); ?>
			<?php comments_template(); ?>
			<?php get_template_part( 'blocks/pager-single', get_post_type() ); ?>
		<?php endwhile; ?>
	</div>
	<?php get_sidebar(); ?>
</section>
<?php get_footer(); ?>