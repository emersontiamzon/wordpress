<?php


class Macho_Widget_Contact extends WP_Widget {

	function __construct() {

		$widget_ops = array(
			'classname'   => 'macho_widget_contact',
			'description' => __( 'A widget that displays contact information. Designed for footer', 'regina' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'macho_widget_contact', __( '[MT] - Contact Info', 'regina' ), $widget_ops );
	}


	function widget( $args, $instance ) {
		global $post;
		$defaults = array(
			'title'                    => null,
			'display_telephone_number' => null,
			'display_fax_number'       => null,
			'display_email_adress'     => null,
			'display_adress'           => null,
			'display_social_media'     => null,
		);

		$instance = wp_parse_args( $instance, $defaults );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		echo '<div class="fixed">';

		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( 'on' == $instance['display_telephone_number'] ) {
			$telephone = get_theme_mod( 'regina_top_telephone_number', '(650) 652-8500' );
			if ( $telephone != '' ) {
				echo '<p><span class="nc-icon-glyph tech_mobile-button white"></span>&nbsp;&nbsp; <a href="tel:' . esc_attr( $telephone ) . '">' . esc_attr( $telephone ) . '</a></p>';
			}
		}

		if ( 'on' == $instance['display_fax_number'] ) {
			$fax = get_theme_mod( 'regina_top_fax_number', '(650) 652-8500' );
			if ( $fax != '' ) {
				echo '<p><span class="nc-icon-glyph tech_print-round white"></span>&nbsp;&nbsp; ' . esc_attr( $fax ) . '</p>';
			}
		}

		if ( 'on' == $instance['display_email_adress'] ) {
			$email = get_theme_mod( 'regina_top_email', 'contact@reginapro.com' );
			if ( $email != '' ) {
				echo '<p><span class="nc-icon-glyph ui-1_email-83 white"></span>&nbsp;&nbsp; <a href="mailto:' . esc_attr( $email ) . '">' . esc_attr( $email ) . '</a></p>';
			}
		}
		if ( 'on' == $instance['display_adress'] ) {
			$adress = get_theme_mod( 'regina_adress', 'Medplus<br>33 Farlane Street<br>Keilor East<br>VIC 3033, New York<br>' );
			if ( $adress != '' ) {
				echo '<div><span style="float:left;" class="nc-icon-glyph location_pin white"></span><p style="float:left; margin:-7px 0 0 10px;">' . htmlspecialchars_decode( $adress ) . '</p><div class="clear"></div></div>';
			}
		}

		if ( 'on' == $instance['display_social_media'] ) {

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

			if ( ! empty( $social_links ) ) {
				echo '<ul class="social-link-list">';
				foreach ( $social_links as $platform => $link ) {
					echo '<li class="' . $platform . '">';
					echo '<a target="_blank" href="' . esc_attr( $link ) . '"><span class="nc-icon-glyph socials-1_logo-' . esc_html( $platform ) . '"></span></a>';
					echo '</li>';
				}
				echo '</ul>';
			}

		}

		echo '</div>';

		echo $args['after_widget'];
	}


	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']                    = esc_attr( $new_instance['title'] );
		$instance['display_telephone_number'] = esc_attr( $new_instance['display_telephone_number'] );
		$instance['display_fax_number']       = esc_attr( $new_instance['display_fax_number'] );
		$instance['display_email_adress']     = esc_attr( $new_instance['display_email_adress'] );
		$instance['display_adress']           = esc_attr( $new_instance['display_adress'] );
		$instance['display_social_media']     = esc_attr( $new_instance['display_social_media'] );


		return $instance;
	}


	function form( $instance ) {

		$defaults = array(
			'title'                    => null,
			'display_telephone_number' => null,
			'display_fax_number'       => null,
			'display_email_adress'     => null,
			'display_adress'           => null,
			'display_social_media'     => null,
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div class="macho-meta">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'regina' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:100%;" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['display_telephone_number'], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_telephone_number' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_telephone_number' ) ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_telephone_number' ); ?>"><?php _e( 'Display telephone number ?', 'regina' ); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['display_fax_number'], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_fax_number' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_fax_number' ) ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_fax_number' ); ?>"><?php _e( 'Display fax number ?', 'regina' ); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['display_email_adress'], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_email_adress' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_email_adress' ) ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_email_adress' ); ?>"><?php _e( 'Display email address ?', 'regina' ); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['display_adress'], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_adress' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_adress' ) ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_adress' ); ?>"><?php _e( 'Display address ?', 'regina' ); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['display_social_media'], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_social_media' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_social_media' ) ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_social_media' ); ?>"><?php _e( 'Display social media links ?', 'regina' ); ?></label>
			</p>

		</div>

		<?php
	}
}


// register the shortcode
register_widget( 'macho_widget_contact' );