<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<div id="blog">
			<?php if ( have_posts() ) : ?>

				<?php
				while ( have_posts() ) :
					the_post();
?>
					<?php get_template_part( 'template-parts/content' ); ?>
				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif ?>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<aside id="sidebar" class="hidden-xs hidden-sm"><?php dynamic_sidebar( 'sidebar-blog' ); ?></aside>
		</div>
	</div>
</div>
<?php get_footer(); ?>
