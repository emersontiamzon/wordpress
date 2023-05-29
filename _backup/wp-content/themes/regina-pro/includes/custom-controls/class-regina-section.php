<?php

class Regina_Section extends WP_Customize_Section {

	public $type = 'regina_section';
	public $slug;
	public $section_id;

	public function json() {
		$json                = parent::json();
		$json['slug']        = $this->slug;
		$json['section_id']  = $this->section_id;
		$json['description'] = esc_html__( 'In order to edit this section content and settings please go ', 'regina' ) . '<a href="' . get_edit_post_link( $this->section_id ) . '" target="_blank">' . __( 'here', 'regina' ) . '</a>';
		return $json;
	}

}
