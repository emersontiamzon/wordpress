<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="medic">
			<?php
			while ( have_posts() ) :
				the_post();
?>
				<?php get_template_part( 'template-parts/content-single', 'member' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
