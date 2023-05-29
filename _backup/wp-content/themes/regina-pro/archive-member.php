<?php get_header(); ?>

<?php

//Sidebar Options
$content_classes = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';


?>
<div class="container">
	<div class="row">
		<div class="<?php echo esc_attr( $content_classes ); ?>">
			<div id="team-block">
				<?php
				while ( have_posts() ) :
					the_post();
?>
					<?php get_template_part( 'template-parts/content', 'member' ); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	<nav id="blog-navigation">
		<?php mt_pagination(); ?>
	</nav>
</div>
<?php get_footer(); ?>
