<?php

class WP_Customize_New_Section extends WP_Customize_Section {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @var string
	 */
	public $type = 'regina_new_section';

	/**
	 * Render the section, and the controls that have been added to it.
	 *
	 * @since 4.3.0
	 */
	protected function render() {
		?>
		<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="accordion-section-new-section">
			<button type="button" class="button add-new-menu-item add-menu-toggle" aria-expanded="false">
				<?php echo esc_html( $this->title ); ?>
			</button>
			<div class="section-content" style="display:none">
				<input type="text" id="new-section-title" class="menu-name-field" placeholder="<?php _e( 'Section Title', 'regina' ); ?>">
				<button type="button" class="button button-primary" id="create-new-section-submit"><?php _e( 'Create Section', 'regina' ); ?></button>
				<span class="spinner"></span>
			</div>
		</li>
		<?php
	}
}
