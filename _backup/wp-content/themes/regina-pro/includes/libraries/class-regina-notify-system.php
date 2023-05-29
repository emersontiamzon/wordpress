<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Regina_Notify_System
 */
class Regina_Notify_System extends Epsilon_Notify_System {

	/**
	 * Verify the status of a plugin
	 *
	 * @param string      $get         Return title/description/etc.
	 * @param string      $slug        Plugin slug.
	 * @param string      $plugin_name Plugin name.
	 * @param bool|string $special     Callback to verify a certain plugin
	 *
	 * @return mixed
	 */
	public static function plugin_verifier( $slug = '', $get = '', $plugin_name = '', $special = false ) {
		if ( false !== $special ) {
			$arr = self::$special();
		} else {
			$arr = array(
				'installed' => Epsilon_Notify_System::check_plugin_is_installed( $slug ),
				'active'    => Epsilon_Notify_System::check_plugin_is_active( $slug ),
			);

			if ( empty( $get ) ) {
				$arr = array_filter( $arr );

				return 2 === count( $arr );
			}
		}
		/* translators: %1$s: plugin name */
		$arr['title']       = sprintf( __( 'Install: %1$s', 'regina' ), $plugin_name );
		/* translators: %1$s: plugin name */
		$arr['description'] = sprintf( __( 'Please install %1$s in order to create the demo content.', 'regina' ), $plugin_name );

		if ( $arr['installed'] ) {
			/* translators: %1$s: plugin name */
			$arr['title']       = sprintf( __( 'Activate: %1$s', 'regina' ), $plugin_name );
			/* translators: %1$s: plugin name */
			$arr['description'] = sprintf( __( 'Please activate %1$s in order to create the demo content.', 'regina' ), $plugin_name );
		}

		return $arr[ $get ];
	}

	/**
	 * Verify that contact form 7 is installed
	 *
	 * @return mixed
	 */
	public static function verify_cf7() {
		$arr = array(
			'installed' => false,
			'active'    => false,
		);

		if ( file_exists( ABSPATH . 'wp-content/plugins/contact-form-7' ) ) {
			$arr['installed'] = true;
			$arr['active']    = defined( 'WPCF7_VERSION' );
		}

		return $arr;
	}

	public static function has_plugin( $slug = null ) {

		$check = array(
			'installed' => self::check_plugin_is_installed( $slug ),
			'active'    => self::check_plugin_is_active( $slug ),
		);

		if ( ! $check['installed'] || ! $check['active'] ) {
			return false;
		}

		return true;
	}

	public static function check_for_content() {

		if ( ! self::has_plugin( 'contact-form-7' ) ) {
			return true;
		}

		$demo_content = get_option( 'regina_pro_imported_demo' );
		if ( $demo_content ) {
			return true;
		}

		return false;

	}
}
