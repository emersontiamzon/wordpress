<?php
if ( class_exists( 'WPForms_Template' ) ) :
	/**
 * Regina Popup Form
 * Template for WPForms.
 */
	class Regina_Form extends WPForms_Template {

		/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
		public function init() {

			// Template name
			$this->name = 'Regina Popup Form';

			// Template slug
			$this->slug = 'regina_popup_form';

			// Template description
			$this->description = 'This form is designed for our popup';

			// Template field and settings
			$this->data = array(
				'field_id' => 7,
				'fields'   => array(
					2 => array(
						'id'          => '2',
						'type'        => 'text',
						'label'       => 'Your name',
						'required'    => '1',
						'size'        => 'large',
						'placeholder' => 'Your name',
						'label_hide'  => '1',
						'css'         => 'regina-name-input input',
					),
					3 => array(
						'id'          => '3',
						'type'        => 'email',
						'label'       => 'Email',
						'required'    => '1',
						'size'        => 'large',
						'placeholder' => 'Email address',
						'label_hide'  => '1',
						'css'         => 'regina-email-input input',
					),
					4 => array(
						'id'          => '4',
						'type'        => 'text',
						'label'       => 'Single Line Text',
						'required'    => '1',
						'size'        => 'large',
						'placeholder' => 'Phone Number',
						'label_hide'  => '1',
						'css'         => 'regina-phone-input input',
					),
					5 => array(
						'id'          => '5',
						'type'        => 'text',
						'label'       => 'Single Line Text',
						'required'    => '1',
						'size'        => 'large',
						'placeholder' => 'Appointment Date',
						'label_hide'  => '1',
						'css'         => 'regina-date-input input',
					),
					6 => array(
						'id'          => '6',
						'type'        => 'textarea',
						'label'       => 'Paragraph Text',
						'size'        => 'medium',
						'placeholder' => 'Message',
						'label_hide'  => '1',
						'css'         => 'regina-message-input input',
					),
				),
				'settings' => array(
					'form_title'                  => 'Regina Popup Form',
					'form_desc'                   => 'This form is designed for our popup',
					'submit_text'                 => 'Send',
					'submit_text_processing'      => 'Sending...',
					'submit_class'                => 'button white outline',
					'honeypot'                    => '1',
					'notification_enable'         => '1',
					'notifications'               => array(
						1 => array(
							'email'          => '{admin_email}',
							'subject'        => 'New Blank Form Entry',
							'sender_name'    => 'Regina Pro',
							'sender_address' => '{admin_email}',
							'message'        => '{all_fields}',
						),
					),
					'confirmation_type'           => 'message',
					'confirmation_message'        => 'Thanks for contacting us! We will be in touch with you shortly.',
					'confirmation_message_scroll' => '1',
					'confirmation_page'           => '11',
				),
				'meta'     => array(
					'template' => 'regina_popup_form',
				),
			);
		}
	}

	new Regina_Form;
endif;
