<?php

//Medic settings
$member_image_id  = mt_get_page_option( get_the_ID(), 'member-image' );
$member_image     = wp_get_attachment_image_src( $member_image_id, 'full' );
$member_hospitals = mt_get_page_option( get_the_ID(), 'member-hospitals' );

?>

<div class="col-lg-3 col-sm-6">
	<div class="team-member">
		<?php if ( isset( $member_image[0] ) ) : ?>
			<img data-original="<?php echo esc_attr( $member_image[0] ); ?>" alt="<?php esc_html( the_title() ); ?>" class="lazy">
		<?php endif ?>

		<div class="inner">
			<h4 class="name"><?php esc_html( the_title() ); ?></h4>
			<p class="position">
				<small><?php echo $member_hospitals; ?></small>
			</p>
		</div>
		<div class="hover">
			<div class="description">
				<p><?php echo esc_html( substr( get_the_excerpt(), 0, 200 ) ); ?></p>
			</div>
			<div class="read-more">
				<a href="<?php echo esc_attr( get_permalink() ); ?>" class="button white outline"><?php _e( 'Read more', 'regina' ); ?>
					<span class="nc-icon-glyph arrows-1_bold-right"></span></a>
			</div>
		</div><!--.hover-->
	</div><!--.team-member-->
</div><!--.col-lg-3-->
