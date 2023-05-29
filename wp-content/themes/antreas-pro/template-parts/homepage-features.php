<?php $query = new WP_Query( 'post_type=cpo_feature&posts_per_page=-1&order=ASC&orderby=menu_order' ); ?>
<?php if ( $query->posts ) : ?>
<div id="features" class="section features">
	<div class="container">	
		<?php antreas_section_heading( 'features' ); ?>
		<?php antreas_grid( $query->posts, 'element', 'feature', antreas_get_option( 'features_columns' ) ); ?>
	</div>
</div>
<?php wp_reset_postdata(); ?>
<?php endif; ?>
