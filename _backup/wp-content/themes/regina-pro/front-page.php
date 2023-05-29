<?php get_header(); ?>

<?php

//Homepage Section Settings
$type_of_section = get_theme_mod( 'regina_homepage_type', 'slider' );

//Homepage Section Texts
$section_title       = get_theme_mod( 'regina_homepage_section_title', __( 'We help people, like you.', 'regina' ) );
$section_description = get_theme_mod( 'regina_homepage_section_description', __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'regina' ) );
$section_button_text = get_theme_mod( 'regina_homepage_section_button_text', __( 'Book Appointment', 'regina' ) );
$section_button_url  = get_theme_mod( 'regina_homepage_section_button_url', '' );

?>

	<section class="homepage-section">
		<div id="home-slider" class="clear">
			<?php
			if ( 'image' == $type_of_section ) {

				$image = get_theme_mod( 'regina_homepage_static_image', '' );
				if ( '' != $image ) {
					echo '<img class="hompega-static-image" src="' . $image . '">';
				}

				?>

				<?php
			} else {

				$default_images = array(
					array(
						'slider_image' => get_template_directory_uri() . '/assets/images/home/slide-1.jpg',
						'slider_url'   => '#',
					),
					array(
						'slider_image' => get_template_directory_uri() . '/assets/images/home/slide-1.jpg',
						'slider_url'   => '#',
					),
				);
				$slides         = get_theme_mod( 'regina_homepage_slider', $default_images );
				if ( ! empty( $slides ) ) {
					echo '<ul class="bxslider clear">';
					foreach ( $slides as $slide ) {
						if ( '' != $slide['slider_image'] ) {

							$slider_image = wp_get_attachment_image_src( $slide['slider_image'], 'slider-image-sizes' );
							if ( isset( $slider_image[0] ) ) {
								$slider_image = $slider_image[0];
							}
							if ( ! $slider_image ) {
								$slider_image = $slide['slider_image'];
							}
							if ( '' != $slide['slider_url'] ) {
								echo '<li><a href="' . esc_attr( $slide['slider_url'] ) . '"><img src="' . $slider_image . '" alt=""></a></li>';
							} else {
								echo '<li><img src="' . $slider_image . '" alt=""></li>';
							}
						}
					}
					echo '</ul>';
				}
			}// End if().
	?>
			<div class="clear"></div>
		</div><!--#home-slider-->

		<?php if ( '' != $section_title || '' != $section_description || '' != $section_button_text ) : ?>
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-12 col-lg-offset-2">
						<div id="call-out" class="clear">
							<?php if ( '' != $section_title ) : ?>
								<h1><?php echo esc_html( $section_title ); ?></h1>
							<?php endif ?>

							<?php if ( '' != $section_description ) : ?>
								<div><?php echo wp_kses_post( $section_description ); ?></div>
							<?php endif ?>

							<br>
							<?php if ( '' != $section_button_text ) : ?>
								<?php if ( '' != $section_button_url ) { ?>
									<a href="<?php echo esc_attr( $section_button_url ); ?>" class="button white outline"><?php echo esc_html( $section_button_text ); ?>
										<span class="nc-icon-glyph arrows-1_bold-right"></span></a>
								<?php } else { ?>
									<a href="#mt-popup-modal" class="button white outline"><?php echo esc_html( $section_button_text ); ?>
										<span class="nc-icon-glyph arrows-1_bold-right"></span></a>
								<?php } ?>
							<?php endif ?>

						</div><!--#call-out-->
					</div><!--.col-md-8-->
				</div>
			</div>
		<?php endif; ?>

	</section>

<?php

$args = array(
	'post_type'      => 'section',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => 'menu_order',
	'posts_per_page' => - 1,
);

if ( ! is_user_logged_in() ) {
	$meta_query                     = array();
	$meta_query[]                   = array(
		'key'     => 'regina_options_section-disable',
		'value'   => '1',
		'compare' => '!=',
	);
	$meta_query[]                   = array(
		'key'     => 'regina_options_section-disable',
		'value'   => '1',
		'compare' => 'NOT EXISTS',
	);
	$args['meta_query']             = $meta_query;
	$args['meta_query']['relation'] = 'OR';
}

$loop = new WP_Query( $args );

if ( $loop->have_posts() ) {
	while ( $loop->have_posts() ) {
		$loop->the_post();

		# Theme Section Args
		$section_title    = mt_get_page_option( get_the_ID(), 'section-heading-title' );
		$section_subtitle = mt_get_page_option( get_the_ID(), 'section-sub-heading-title' );

		# Verify if section is full width
		$section_full_width = mt_get_page_option( get_the_ID(), 'section-full-width' );

		#Get Page Style
		$section_style_option = mt_get_page_option( get_the_ID(), 'section-style' );

		# Background Related
		$section_background_color = mt_get_page_option( $loop->post->ID, 'section-bg-image', 'color' ) !== false ? mt_get_page_option( $loop->post->ID, 'section-bg-image', 'color' ) : '#FFF';
		$section_bg_image         = mt_get_page_option( $loop->post->ID, 'section-bg-image', 'background-image' ) !== false ? mt_get_page_option( $loop->post->ID, 'section-bg-image', 'background-image' ) : false;
		$section_bg_image_opacity = mt_get_page_option( $loop->post->ID, 'section-bg-image', 'transparent' ) !== false ? mt_get_page_option( $loop->post->ID, 'section-bg-image', 'transparent' ) : 100;

		$slug = get_post_field( 'post_name', get_the_ID() );

		$section_style = '';
		$class         = '';
		if ( $section_bg_image ) {
			$section_style = 'style="background:url(' . $section_bg_image . ') no-repeat;background-size: cover;background-position: center;"';
		} elseif ( 0 != intval( $section_bg_image_opacity ) ) {
			$section_style = 'style="background:' . mt_hex2rgba( $section_background_color, intval( $section_bg_image_opacity ) / 100 ) . '"';
		}

		if ( 'with-padding' == $section_style_option ) {
			$class = 'has-padding';
		}

		echo '<section id="' . $slug . '" class="homepage-section ' . $class . '" ' . $section_style . '>';

		if ( $section_bg_image && 0 != intval( $section_bg_image_opacity ) ) {
			echo '<div class="section-overlay" style="background:' . mt_hex2rgba( $section_background_color, intval( $section_bg_image_opacity ) / 100 ) . '"></div>';
		}
		if ( '' != $section_title || '' != $section_subtitle ) {
			echo '<div class="container">';
			echo '<div class="row">';
			echo '<div class="col-xs-12">';
			echo '<div class="section-info">';
			if ( '' != $section_title ) {
				echo '<h2>' . esc_html( $section_title ) . '</h2>';
				echo '<hr>';
			}
			if ( '' != $section_subtitle ) {
				echo '<p>' . esc_html( $section_subtitle ) . '</p>';
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		if ( ! $section_full_width ) {
			echo '<div class="container">';
			echo '<div class="row">';
			the_content();
			echo '</div>';
			echo '</div>';
		} else {
			echo '<div class="full-width-content">';
			the_content();
			echo '</div>';
		}
		echo '</section>';

	}// End while().
}// End if().

?>

<?php get_footer(); ?>
