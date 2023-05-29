<?php
/*
* Template Name: Full Width
*/
?>
<?php get_header(); ?>

<div class="row">
	<div class="entry-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?php
		while ( have_posts() ) :
			the_post();
?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>
