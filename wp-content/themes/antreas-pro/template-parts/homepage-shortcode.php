<?php
	$home_shortcode_content = antreas_get_option( 'home_shortcode_content' );
?>

<div id="shortcode" class="section shortcode">
	<div class="container">	
		<?php antreas_section_heading( 'shortcode' ); ?>
	
		<div class="row">
			<?php if ( $home_shortcode_content ) { ?>
				<?php echo do_shortcode( $home_shortcode_content ); ?>
			<?php } ?>
		</div>
	</div>
</div>
