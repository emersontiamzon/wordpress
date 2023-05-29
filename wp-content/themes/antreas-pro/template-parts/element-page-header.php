<?php wp_reset_query(); ?>

<?php if ( antreas_show_title() ) : ?>

	<?php $header_image = antreas_header_image(); ?>

	<?php
	if ( '' != $header_image && ! ( is_page() && has_post_thumbnail() ) ) {
		$header_image = 'style="background-image:url(' . esc_url( $header_image ) . ');"';
	} else {
		$header_image = 'style="background-image:url(' . esc_url( get_the_post_thumbnail_url() ) . ');"';
	}
	
	$title_area_overlay_color      = get_post_meta( get_the_ID(), 'title_area_overlay_color', true ); 
	$title_area_overlay_opacity    = get_post_meta( get_the_ID(), 'title_area_overlay_opacity', true ); 
	?>
	<?php do_action( 'antreas_before_title' ); ?>
	<section id="pagetitle" class="pagetitle dark" <?php echo $header_image; ?>>
		<div class="pagetitle__overlay" style="<?php echo $title_area_overlay_color ? "background-color:{$title_area_overlay_color};" : ''; ?> <?php echo $title_area_overlay_opacity ? "opacity:{$title_area_overlay_opacity};" : ''; ?>"></div>	
					
		<div class="container">
			<?php do_action( 'antreas_title' ); ?>
		</div>
	</section>
	<?php do_action( 'antreas_after_title' ); ?>

<?php endif; ?>
