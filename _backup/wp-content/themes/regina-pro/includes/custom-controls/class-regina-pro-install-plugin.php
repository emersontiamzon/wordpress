<?php
/**
 *  Custom Control: Install Plugin
 */
if ( ! class_exists( 'Regina_PRO_Install_Plugin' ) ) {
	class Regina_PRO_Install_Plugin extends WP_Customize_Control {
		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.1.0
		 * @access public
		 * @var    string
		 */
		public $type = 'regina-pro-install-plugin';
		/**
		 * @var string
		 */
		public $description = '';
		/**
		 * @var array
		 */
		public $slug = array();
		/**
		 * Regina_PRO_Install_Plugin constructor.
		 *
		 * @since 1.1.0
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string               $id
		 * @param array                $args
		 */
		public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
			parent::__construct( $manager, $id, $args );
			$manager->register_control_type( 'Regina_PRO_Install_Plugin' );
		}
		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since  1.1.0
		 * @access public
		 */
		public function json() {
			$json                 = parent::json();
			$json['id']           = $this->id;
			$json['link']         = $this->get_link();
			$json['value']        = $this->value();
			$json['action']       = $this->check_plugin( $this->slug );
			$json['description']  = $this->description;
			$json['slug']         = $this->slug;
			$json['plugin_link']  = $this->create_plugin_link( $json['action'], $this->slug );
			$json['button_label'] = array(
				'activate' => esc_html__( 'Activate', 'regina' ),
				'install'  => esc_html__( 'Install', 'regina' ),
			);
			$json['labels']       = array(
				'activate' => esc_html__( 'Activating ...', 'regina' ),
				'install'  => esc_html__( 'Installing ...', 'regina' ),
			);
			return $json;
		}

		private function check_plugin( $slug ) {

			if ( Epsilon_Notify_System::check_plugin_is_installed( $slug ) ) {
				if ( Epsilon_Notify_System::check_plugin_is_active( $slug ) ) {
					return 'deactivate';
				}
				return 'activate';
			}

			return 'install';

		}

		private function create_plugin_link( $state, $slug ) {
			$string = '';
			switch ( $state ) {
				case 'install':
					$string = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => Epsilon_Notify_System::_get_plugin_basename_from_slug( $slug ),
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_' . $slug
					);
					break;
				case 'deactivate':
					$string = add_query_arg(
						array(
							'action'        => 'deactivate',
							'plugin'        => Epsilon_Notify_System::_get_plugin_basename_from_slug( $slug ),
							'plugin_status' => 'all',
							'paged'         => '1',
							'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . Epsilon_Notify_System::_get_plugin_basename_from_slug( $slug ) ),
						),
						admin_url( 'plugins.php' )
					);
					break;
				case 'activate':
					$string = add_query_arg(
						array(
							'action'        => 'activate',
							'plugin'        => Epsilon_Notify_System::_get_plugin_basename_from_slug( $slug ),
							'plugin_status' => 'all',
							'paged'         => '1',
							'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . Epsilon_Notify_System::_get_plugin_basename_from_slug( $slug ) ),
						),
						admin_url( 'plugins.php' )
					);
					break;
			}// End switch().

			return $string;
		}

		/**
		 * Display the control's content
		 */
		public function content_template() {
			//@formatter:off ?>
			<div class="epsilon-control-container">
				<label>
					<span class="customize-control-title">{{{ data.label }}}</span>
				</label>
				<# if( data.description ){ #>
				<span class="description customize-control-description"><p>{{{data.description}}}</p></span>
				<# } #>
				<div class="regina-pro-install-button" style="text-align:center;">
					<a href="#" class="regina-pro-install-button button button-primary">{{ data.button_label[ data.action ] }}</a>
				</div>
			</div>
		<?php
		//@formatter: on
		}
	}
}
