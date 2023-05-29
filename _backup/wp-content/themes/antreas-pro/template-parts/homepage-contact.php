<?php
$home_contact         = antreas_get_option( 'home_contact' );
$home_contact_content = antreas_get_option( 'home_contact_content' );
$contact_form_plugin  = antreas_get_option( 'plugin_select' );
$form_id              = antreas_get_option( 'form_id' );

if ( $home_contact === '' ) {
	return;
}

if ( $contact_form_plugin === 'wpforms' ) {
	$shortcode_tag = 'wpforms';
} elseif ( $contact_form_plugin === 'cf7' ) {
	$shortcode_tag = 'contact-form-7';
} elseif ( $contact_form_plugin === 'kali-forms' ) {
	$shortcode_tag = 'kaliform';
}
?>

<div id="contact" class="section contact">
	<div class="container">
		<?php antreas_section_heading( 'contact' ); ?>

		<div class="row">
			<?php if ( $home_contact_content ) { ?>
				<div class="column col2 contact__content">
					<?php wp_enqueue_style( 'antreas-fontawesome' ); ?>
					<?php echo do_shortcode( $home_contact_content ); ?>
				</div>
			<?php } ?>
			<?php if ( isset($shortcode_tag) && $shortcode_tag !== '' && $form_id !== '' && $form_id !== 'default' ) { ?>
				<div class="column col2 contact__form">
					<?php echo do_shortcode( '[' . $shortcode_tag . ' id="' . $form_id . '"]' ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
