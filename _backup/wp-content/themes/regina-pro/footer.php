<?php
$footer_widgets        = get_theme_mod( 'regina_footer_widgets', 1 );
$modal_form_type       = get_theme_mod( 'regina_booking_form_opions', 'custom-form' );
$modal_kaliforms_form  = get_theme_mod( 'regina_kaliforms_form_id', '0' );
$modal_cf7_form        = get_theme_mod( 'regina_booking_form_id', '0' );
$modal_wpforms_form    = get_theme_mod( 'regina_wpforms_form_id', '0' );
?>

<div id="mt-popup-modal" class="modaloverlay">
	<div class="mt-modal">
		<a href="#close" class="mt-close">&times;</a>

		<h1><?php echo __( 'Make an appointment and we’ll contact you.', 'regina' ); ?></h1>
		<div id="appointment-form">
			<?php
			if ( defined('KALIFORMS_VERSION') && '0' != $modal_kaliforms_form ) {
				echo do_shortcode( '[kaliform id="' . $modal_kaliforms_form . '"]' );
			} elseif ( 'contact-form-7' == $modal_form_type && '0' != $modal_cf7_form ) {
				echo do_shortcode( '[contact-form-7 id="' . $modal_cf7_form . '"]' );
			} elseif ( 'wpforms' == $modal_form_type && '0' != $modal_wpforms_form ) {
				echo do_shortcode( '[wpforms id="' . $modal_wpforms_form . '"]' );
			} else {
				?>
				<div class="row">
					<form>
						<div class="name input">
							<input type="text" placeholder="<?php echo __( 'Your name', 'regina' ); ?>">
						</div>

						<div class="email input">
							<input type="text" placeholder="<?php echo __( 'Email address', 'regina' ); ?>">
						</div>

						<div class="phone input">
							<input type="text" placeholder="<?php echo __( 'Phone Number', 'regina' ); ?>">
						</div>

						<div class="date input">
							<input type="text" placeholder="<?php echo __( 'Appointment Date', 'regina' ); ?>'">
						</div>

						<div class="message input">
							<textarea placeholder="<?php echo __( 'Message', 'regina' ); ?>"></textarea>
						</div>

						<input type="submit" value="<?php echo __( 'Send', 'regina' ); ?>" class="button white outline" id="send-appointment">
					</form>
				</div>
			<?php } ?>
		</div>

		<div class="succes" style="display:none"><h1><?php echo __( 'Message Sent', 'regina' ); ?></h1></div>

	</div>
</div>

<?php
if ( $footer_widgets ) {
	?>

	<footer id="footer">
		<div class="container">
			<div class="row">
				<?php
				$footer_columns_v2 = get_theme_mod( 'regina_footer_columns_v2' );
				if ( '' != $footer_columns_v2 && $footer_columns_v2 ) {
					if ( ! is_array( $footer_columns_v2 ) ) {
						$footer_layout = json_decode( $footer_columns_v2, true );
					} else {
						$footer_layout = $footer_columns_v2;
					}
				} else {
					$footer_layout = array(
						'columnsCount' => 4,
						'columns'      => array(
							array(
								'index' => 1,
								'span'  => 3,
							),
							array(
								'index' => 2,
								'span'  => 3,
							),
							array(
								'index' => 3,
								'span'  => 3,
							),
							array(
								'index' => 4,
								'span'  => 3,
							),
						),
					);
				}
				foreach ( $footer_layout['columns'] as $key => $layout ) {
					echo '<div class="col-sm-' . $layout['span'] . '">';
					if ( 1 == $layout['index'] ) {
						$sidebar = 'sidebar-footer';
					} else {
						$sidebar = 'sidebar-footer-' . $layout['index'];
					}

					dynamic_sidebar( $sidebar );
					echo '</div>';
				}
				?>
				<a href="#" class="back-to-top"><span class="nc-icon-glyph arrows-1_bold-up"></span></a>
			</div><!--.row-->
		</div><!--.container-->
	</footer><!--#footer-->

<?php
}// End if().
	?>

<footer id="sub-footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<p class="left site-copyright">
					<?php echo get_theme_mod( 'regina_footer_copyright', '&copy; ' . date( 'Y' ) . __( ' Regina. All Rights Reserved.', 'regina' ) ); ?>
				</p>
				<?php

				$nav_args = array(
					'theme_location' => 'footer',
					'menu_class'     => 'link-list hidden-xs',
					'menu_id'        => 'footer-menu',
					'container'      => false,
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'fallback_cb'    => false,
				);

				wp_nav_menu( $nav_args );

				?>
			</div>
		</div><!--.row-->
	</div><!--.container-->
</footer><!--#sub-footer-->

<?php wp_footer(); ?>
<?php

$custom_js = get_theme_mod( 'regina_custom_js', '' );
if ( '' != $custom_js ) {
	echo '<script type="text/javascript">' . $custom_js . '</script>';
}

?>
</body>
</html>
