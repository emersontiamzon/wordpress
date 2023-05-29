<?php


$icon           = mt_get_page_option( get_the_ID(), 'service-icon' );
$icon_image_id  = mt_get_page_option( get_the_ID(), 'service-icon-image' );
$icon_image     = wp_get_attachment_image_src( $icon_image_id, 'full' );
$read_more_text = mt_get_page_option( get_the_ID(), 'service-read-more-text' );
$read_more_url  = mt_get_page_option( get_the_ID(), 'service-read-more-link' );

?>
<div class="col-lg-3 col-sm-6">
	<div class="service">
		<?php if ( '' != $icon || '' != $icon_image ) : ?>
			<div class="icon">
				<?php if ( '' != $read_more_url ) { ?>
					<a href="<?php echo $read_more_url; ?>" class="link">
				<?php } ?>
				<?php if ( '' != $icon_image ) { ?>
					<img src="<?php echo $icon_image[0]; ?>">
				<?php } else { ?>
					<span class="<?php echo str_replace( '|', ' ', $icon ); ?>"></span>
				<?php } ?>
				<?php if ( '' != $read_more_url ) { ?>
					</a>
				<?php } ?>
			</div>
		<?php endif; ?>


		<h3><?php esc_html( the_title() ); ?></h3>
		<p><?php the_content(); ?></p>
		<br>
		<?php if ( '' != $read_more_text && '' != $read_more_url ) : ?>
			<a href="<?php echo esc_attr( $read_more_url ); ?>" class="link small"><?php echo esc_html( $read_more_text ); ?>
				<span class="nc-icon-glyph arrows-1_bold-right"></span></a>
		<?php endif ?>
	</div><!--.service-->
</div><!--.col-lg-3-->
