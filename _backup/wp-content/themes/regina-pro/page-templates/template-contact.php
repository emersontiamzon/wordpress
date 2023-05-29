<?php
/*
* Template Name: Contact
*/
?>
<?php get_header(); ?>
<?php

$telephone = get_theme_mod( 'regina_top_telephone_number', '(650) 652-8500' );
$fax       = get_theme_mod( 'regina_top_fax_number', '(650) 652-8500' );
$email     = get_theme_mod( 'regina_top_email', ' contact@reginapro.com' );
$adress    = get_theme_mod( 'regina_adress', 'Medplus<br>33 Farlane Street<br>Keilor East<br>VIC 3033, New York<br>' );
$job_email = get_theme_mod( 'regina_jobs_email', 'jobs@reginapro.com' );

$social_links     = array();
if ( get_theme_mod( 'regina_top_facebook', '#' ) != '' ) {
	$social_links['facebook'] = get_theme_mod( 'regina_top_facebook', '#' );
}
if ( get_theme_mod( 'regina_top_instagram', '#' ) != '' ) {
	$social_links['instagram'] = get_theme_mod( 'regina_top_instagram', '#' );
}
if ( get_theme_mod( 'regina_top_twitter', '#' ) != '' ) {
	$social_links['twitter'] = get_theme_mod( 'regina_top_twitter', '#' );
}
if ( get_theme_mod( 'regina_top_linkedin', '#' ) != '' ) {
	$social_links['linkedin'] = get_theme_mod( 'regina_top_linkedin', '#' );
}
if ( get_theme_mod( 'regina_top_youtube', '#' ) != '' ) {
	$social_links['youtube'] = get_theme_mod( 'regina_top_youtube', '#' );
}
if ( get_theme_mod( 'regina_top_yelp', '#' ) != '' ) {
	$social_links['yelp'] = get_theme_mod( 'regina_top_yelp', '#' );
}
if ( get_theme_mod( 'regina_top_gplus', '#' ) != '' ) {
	$social_links['google-plus'] = get_theme_mod( 'regina_top_gplus', '#' );
}

?>
	<div class="section has-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pull-right">
					<?php
					while ( have_posts() ) :
						the_post();
?>
						<h2><?php the_title(); ?></h2>
						<hr>
						<?php the_content(); ?>
					<?php endwhile; ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<h2><?php _e( 'Details', 'regina' ); ?></h2>
					<hr>
					<ul class="contact-methods">
						<?php if ( '' != $adress ) : ?>
							<li>
								<span class="nc-icon-glyph location_pin"></span>
								<?php echo htmlspecialchars_decode( $adress ); ?>
							</li>
						<?php endif ?>
						<?php if ( '' != $telephone ) : ?>
							<li>
								<span class="nc-icon-glyph ui-2_phone"></span><strong><?php _e( 'Phone', 'regina' ); ?>:</strong> <?php echo esc_attr( $telephone ); ?>
							</li>
						<?php endif ?>
						<?php if ( '' != $fax ) : ?>
							<li>
								<span class="nc-icon-glyph tech_print-round"></span>
								<strong><?php _e( 'Fax', 'regina' ); ?>:</strong> <?php echo esc_attr( $fax ); ?>
							</li>
						<?php endif ?>
						<?php if ( '' != $email ) : ?>
							<li>
								<span class="nc-icon-glyph ui-1_email-83"></span><strong><?php _e( 'Email', 'regina' ); ?>:</strong> <?php echo esc_attr( $email ); ?>
							</li>
						<?php endif ?>

					</ul><!--.contact-methods-->
					<?php if ( ! empty( $social_links ) ) : ?>
						<h2><?php _e( 'Social', 'regina' ); ?></h2>
						<ul class="contact-social">
							<?php foreach ( $social_links as $platform => $link ) { ?>
								<li class="<?php echo $platform; ?>">
									<a target="_blank" href="<?php echo esc_attr( $link ); ?>"><span class="nc-icon-glyph socials-1_logo-<?php echo esc_html( $platform ); ?>"></span></a>
								</li>
							<?php } ?>
						</ul>
					<?php endif ?>
					<?php if ( '' != $job_email ) : ?>
						<p>
							<small><?php _e( 'For job inquires use', 'regina' ); ?>:</small>
							<br>
							<a href="mailto:<?php echo esc_attr( $job_email ); ?>"><?php echo esc_attr( $job_email ); ?></a>
						</p>
					<?php endif ?>

					<div class="spacer-10x visible-xs"></div>
				</div>
			</div>
		</div>
	</div>
<?php echo do_shortcode( '[map disablecontrols="true" enablescrollwheel="false" height="400px" zoom="13" hide_xs="false" hide_sm="false" hide_md="false" hide_lg="false"][/map]' ); ?>
<?php get_footer(); ?>
