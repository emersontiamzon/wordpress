<?php get_header(); ?>

<div id="main" class="main">
	<div class="container">
		<section id="content" class="content">
			<?php do_action( 'antreas_before_content' ); ?>
			
			<?php $description = term_description(); ?>
			<?php if ( $description !== '' ) : ?>
				<div class="page-content">
					<?php echo $description; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( have_posts() ) : ?>
				<div id="team" class="team">
					<?php antreas_grid( null, 'element', 'team', antreas_get_option( 'team_columns' ), array( 'class' => 'column-narrow' ) ); ?>
				</div>
			<?php endif; ?>
			<?php antreas_numbered_pagination(); ?>
			
			<?php do_action( 'antreas_after_content' ); ?>
		</section>
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>