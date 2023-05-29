<?php

$telephone_number = get_theme_mod( 'regina_top_telephone_number', '(650) 652-8500' );
$email_adress     = get_theme_mod( 'regina_top_email', 'contact@mediplus.com' );
$social_links     = array();
if ( get_theme_mod( 'regina_top_header_enable_facebook', true ) && get_theme_mod( 'regina_top_facebook', '#' ) != '' ) {
	$social_links['facebook'] = get_theme_mod( 'regina_top_facebook', '#' );
}
if ( get_theme_mod( 'regina_top_header_enable_instagram', true ) && get_theme_mod( 'regina_top_instagram', '#' ) != '' ) {
	$social_links['instagram'] = get_theme_mod( 'regina_top_instagram', '#' );
}
if ( get_theme_mod( 'regina_top_header_enable_twitter', true ) && get_theme_mod( 'regina_top_twitter', '#' ) != '' ) {
	$social_links['twitter'] = get_theme_mod( 'regina_top_twitter', '#' );
}
if ( get_theme_mod( 'regina_top_header_enable_linkedin', true ) && get_theme_mod( 'regina_top_linkedin', '#' ) != '' ) {
	$social_links['linkedin'] = get_theme_mod( 'regina_top_linkedin', '#' );
}
if ( get_theme_mod( 'regina_top_header_enable_youtube', true ) && get_theme_mod( 'regina_top_youtube', '#' ) != '' ) {
	$social_links['youtube'] = get_theme_mod( 'regina_top_youtube', '#' );
}
if ( get_theme_mod( 'regina_top_header_enable_yelp', true ) && get_theme_mod( 'regina_top_yelp', '#' ) != '' ) {
	$social_links['yelp'] = get_theme_mod( 'regina_top_yelp', '#' );
}
if ( get_theme_mod( 'regina_top_header_enable_gplus', true ) && get_theme_mod( 'regina_top_gplus', '#' ) != '' ) {
	$social_links['google-plus'] = get_theme_mod( 'regina_top_gplus', '#' );
}

$show_search_in_menu = get_theme_mod( 'regina_header_search', true );

?>
<header id="sub-header" class="">
	<div class="container">
		<div class="row">
			<div class="col-md-3 hidden-sm col-xs-12 text-left-lg text-left-md text-left-sm text-center-xs">

				<?php if ( ! empty( $social_links ) ) : ?>
					<ul class="social-link-list">
						<?php foreach ( $social_links as $platform => $link ) { ?>
							<li class="<?php echo $platform; ?>">
								<a target="_blank" href="<?php echo esc_attr( $link ); ?>"><span class="nc-icon-glyph socials-1_logo-<?php echo esc_html( $platform ); ?>"></span></a>
							</li>
						<?php } ?>
					</ul>
				<?php endif ?>

			</div>
			<div class="col-md-9 col-sm-12 col-xs-12 text-center-xs">
				<?php if ( $show_search_in_menu ) { ?>
					<div class="col-sm-5 pull-right">
						<div class="nav-menu-search">
							<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url() ); ?>">
								<input type="text" name="s" class="search-field" id="search" placeholder="<?php _e( 'Search', 'regina' ); ?>" value="<?php echo get_search_query(); ?>">
								<button class="icon"><i class="fa fa-search"></i></button>
							</form>
						</div>
					</div>
				<?php } ?>
				<div class="col-sm-7 pull-right">
					<?php if ( get_theme_mod( 'regina_top_header_enable_telephone', true ) && '' != $telephone_number ) : ?>
						<p class="phone-number"><span class="nc-icon-glyph tech_mobile-button"></span>&nbsp;&nbsp;
							<a href="tel:<?php echo esc_attr( $telephone_number ); ?>"><?php echo esc_html( $telephone_number ); ?></a>
						</p>
					<?php endif ?>
					<?php if ( get_theme_mod( 'regina_top_header_enable_email', true ) && '' != $email_adress ) : ?>
						<p class="email">
							<span class="nc-icon-glyph ui-1_email-83"></span>&nbsp;&nbsp;<a href="mailto:<?php echo esc_attr( $email_adress ); ?>"><?php echo esc_html( $email_adress ); ?></a>
						</p>
					<?php endif ?>
				</div>

			</div><!--.col-xs-12-->
		</div><!--.row-->
	</div><!--.container-->
</header><!--#sub-header-->
