<?php

$show_appointment_in_menu = get_theme_mod( 'regina_header_book_appointment', true );
$appointment_icon         = 'nc-icon-glyph ui-1_bell-53';
$appointment_text         = get_theme_mod( 'regina_book_appointment_text', 'Book Appointment' );
$appointment_url          = get_theme_mod( 'regina_homepage_section_button_url', '' );
if ( '' == $appointment_url ) {
	$appointment_url = '#mt-popup-modal';
}
$show_search_in_menu      = get_theme_mod( 'regina_header_search', true );

?>
<header id="header">
	<div class="container">
		<div class="row">

			<div class="col-lg-3 col-sm-12">
				<div id="logo">
					<?php regina_pro_logo(); ?>
				</div><!--#logo-->

				<button class="mobile-nav-btn"><span class="nc-icon-glyph ui-2_menu-bold"></span></button>
			</div><!--.col-lg-3-->

			<div class="col-lg-9 col-sm-12">
				<nav id="navigation">
					<ul class="main-menu">
						<?php

						if ( has_nav_menu( 'primary' ) ) {
							$nav_args = array(
								'theme_location' => 'primary',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'walker' => new Regina_Walker_Nav(),
							);

							wp_nav_menu( $nav_args );
						}

						?>
						<?php if ( $show_appointment_in_menu ) : ?>
							<li class="hide-mobile">
								<a href="<?php echo $appointment_url; ?>" title="<?php _e( 'Book Appointment', 'regina' ); ?>"  class="appointment-link"><span class="<?php echo esc_attr( $appointment_icon ); ?>"></span> <?php echo esc_html( $appointment_text ); ?>
								</a></li>
						<?php endif ?>

					</ul>
					<div class="nav-search-box hidden-xs hidden-sm hidden-md hidden-lg">
						<input type="text" placeholder="<?php _e( 'Search', 'regina' ); ?>" value="<?php echo get_search_query(); ?>">
						<button class="search-btn"><span class="nc-icon-outline ui-1_zoom"></span></button>
					</div>
				</nav><!--#navigation-->
			</div><!--.col-lg-9-->
		</div><!--.row-->
	</div><!--.container-->
</header><!--#header-->
