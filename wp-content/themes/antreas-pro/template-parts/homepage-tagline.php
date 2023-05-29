
<?php
$home_tagline = antreas_get_option( 'home_tagline' );
if ( $home_tagline === '' ) {
	return;
}
?>

<div id="tagline" class="section tagline dark">
	<div class="container">
		<div class="tagline-body">
			<?php antreas_tagline_title(); ?>
			<?php antreas_tagline_content(); ?>
			<?php antreas_tagline_link( 'button button-medium' ); ?>
		</div>
		<?php antreas_tagline_image(); ?>
	</div>
</div>
