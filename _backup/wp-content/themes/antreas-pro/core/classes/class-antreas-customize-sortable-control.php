<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class Antreas_Customize_Sortable_Control extends WP_Customize_Control {

	public function enqueue() {
		wp_enqueue_script( 'antreas-sortable-control', ANTREAS_ASSETS_JS . 'customizer-controls/sortable-control.js', array( 'jquery-ui-sortable' ), ANTREAS_VERSION );
	}

	public function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		}

		$name             = $this->id;
		$field_list       = explode( ',', $this->value() );
		$remaining_fields = $this->choices;
		?>

		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>

		<?php
		$output  = '';
		$output .= '<input type="hidden" class="cpotheme-sortable-value" ' . $this->get_link() . ' name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" value="' . $this->value() . '"/>';
		$output .= '<div class="cpotheme-sortable">';
		$count   = 100;
		foreach ( $field_list as $current_element ) {
			foreach ( $this->choices as $list_key => $list_value ) {
				if ( $current_element != '' && $current_element == $list_key ) {
					$output .= '<div data-key="' . esc_attr( $list_key ) . '" class="cpotheme-sortable-item" for="' . esc_attr( $name ) . '_' . $list_key . '">';
					$output .= '<div class="cpotheme-sortable-field">' . $count . '</div>';
					$output .= '<div class="cpotheme-sortable-name">' . $list_value . '</div>';
					$output .= '</div>';
					$count  += 100;
					unset( $remaining_fields[ $list_key ] );
				}
			}
		}
		//Add remaining fields to ensure list is complete
		foreach ( $remaining_fields as $list_key => $list_value ) {
			$output .= '<div data-key="' . esc_attr( $list_key ) . '" class="cpotheme-sortable-item" for="' . esc_attr( $name ) . '_' . $list_key . '">';
			$output .= '<div class="cpotheme-sortable-field">' . $count . '</div>';
			$output .= '<div class="cpotheme-sortable-name">' . $list_value . '</div>';
			$output .= '</div>';
			$count  += 100;
		}

		$output .= '</div>';
		echo $output;
	}
}
