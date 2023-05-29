<?php

//Medic settings
$member_image_id  = mt_get_page_option( get_the_ID(), 'member-image' );
$member_position  = mt_get_page_option( get_the_ID(), 'member-position' );
$member_hospitals = mt_get_page_option( get_the_ID(), 'member-hospitals' );
$member_hour_mf   = mt_get_page_option( get_the_ID(), 'member-hour-mf' );
$member_hour_sat  = mt_get_page_option( get_the_ID(), 'member-hour-sat' );
$member_hour_sun  = mt_get_page_option( get_the_ID(), 'member-hour-sun' );
$member_social    = mt_get_page_option( get_the_ID(), 'member-social' );

// book appointment options
$book_appointment_toggle = mt_get_page_option( get_the_ID(), 'book-appointment-toggle' );
$book_appointment_label  = mt_get_page_option( get_the_ID(), 'book-appointment-label' );
$book_appointment_url    = mt_get_page_option( get_the_ID(), 'book-appointment-url' );

?>

<div class="col-xs-12 col-sm-5 col-md-offset-0">
	<div class="medic-meta">
		<?php
		if ( $member_image_id ) :
			$member_image = wp_get_attachment_image_src( $member_image_id, 'full' );
			?>
			<img data-original="<?php echo esc_attr( $member_image[0] ); ?>" alt="<?php echo esc_html( get_the_title() ); ?>" class="lazy">
		<?php endif ?>

		<div class="inner">
			<h4 class="name"><?php echo esc_html( get_the_title() ); ?></h4>
			<p class="position">
				<small><?php echo esc_html( $member_hospitals ); ?></small>
			</p>

			<?php if ( $member_social ) : ?>
				<ul class="social">
					<?php

					foreach ( $member_social as $social ) {
						echo '<li class="facebook"><a href="' . esc_attr( $social['members-social-url'] ) . '"><span class="' . str_replace( '|', ' ', $social['members-social-icon'] ) . '"></span></a></li>';
					}

					?>
				</ul>
			<?php endif ?>

			<?php if ( '' != $member_hour_mf || '' != $member_hour_sat || '' != $member_hour_sun ) : ?>
				<ul class="work-hours">
					<?php if ( '' != $member_hour_mf ) : ?>
						<li>
							<p>
								<strong><?php _e( 'Monday - Friday', 'regina' ); ?></strong>
								<br>
								<?php echo esc_html( $member_hour_mf ); ?>
							</p>
						</li>
					<?php endif ?>
					<?php if ( '' != $member_hour_sat ) : ?>
						<li>
							<p>
								<strong><?php _e( 'Saturday', 'regina' ); ?></strong>
								<br>
								<?php echo esc_html( $member_hour_sat ); ?>
							</p>
						</li>
					<?php endif ?>
					<?php if ( '' != $member_hour_sun ) : ?>
						<li>
							<p>
								<strong><?php _e( 'Sunday', 'regina' ); ?></strong>
								<br>
								<?php echo esc_html( $member_hour_sun ); ?>
							</p>
						</li>
					<?php endif ?>
				</ul><!--.work-hours-->
			<?php endif ?>
			<?php if ( '0' == $book_appointment_toggle ) { ?>
				<a href="#mt-popup-modal" class="button"><?php _e( 'Book Appointment', 'regina' ); ?>
					<span class="nc-icon-glyph arrows-1_bold-right"></span>
				</a>
			<?php } elseif ( ! empty( $book_appointment_label ) && ! empty( $book_appointment_url ) ) { ?>
				<a href="<?php echo esc_url( $book_appointment_url ); ?>" class="button"><?php echo esc_html( $book_appointment_label ); ?>
					<span class="nc-icon-glyph arrows-1_bold-right"></span>
				</a>
			<?php } ?>

		</div><!--.inner-->
	</div><!--.medic-meta-->

	<div class="spacer-3x visible-xs"></div>
</div><!--.col-sm-4-->

<div class="col-xs-12 col-sm-7 col-md-offset-0">
	<div class="medic-description">
		<h2 class="name"><?php echo esc_html( get_the_title() ); ?></h2>
		<p class="position"><?php echo esc_html( $member_position ); ?></p>

		<?php the_content(); ?>
	</div><!--.medic-description-->
</div><!--.col-sm-8-->
