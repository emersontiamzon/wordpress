<?php

class WP_Customize_New_Section_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @var string
	 */
	public $type = 'regina_new_section';

	/**
	 * Render the control's content.
	 *
	 * @since 4.3.0
	 */
	public function render_content() {
		?>
		<input type="text" id="new-section-title" value="">
		<button type="button" class="button button-primary" id="create-new-section-submit"><?php _e( 'Create Section', 'regina' ); ?></button>
		<span class="spinner"></span>
		<?php
	}
}
