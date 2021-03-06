<?php
class MS_View_Shortcode_RegisterUser extends MS_View {

	/**
	 * Returns the HTML code.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function to_html() {
		// If user is logged in, they obviously cannot register again ;)
		if ( is_user_logged_in() ) { return ''; }

		$fields = $this->prepare_fields();

		$this->add_action(
			'ms_shortcode_register_form_end',
			'add_scripts'
		);

		// When redirecting to login form we want to keep the previously submitted form data.
		$url_data = $_POST;
		$url_data['do-login'] = '1';
		$login_url = esc_url_raw( add_query_arg( $url_data ) );

		if ( ! empty( $_REQUEST['do-login'] ) ) {
			$register_url = esc_url_raw( remove_query_arg( 'do-login' ) );

			$back_link = array(
				'url' => $register_url,
				'class' => 'alignleft',
				'value' => __( 'Cancel', 'membership2' ),
			);

			$html = do_shortcode(
				sprintf(
					'[%s show_note=false title="%s"]',
					MS_Helper_Shortcode::SCODE_LOGIN,
					__( 'Login', 'membership2' )
				)
			);
			$html .= MS_Helper_Html::html_link( $back_link, true );
			return $html;
		}

		$login_link = array(
			'title' => __( 'Login', 'membership2' ),
			'url' => $login_url,
			'class' => 'alignleft',
			'value' => __( 'Already have a user account?', 'membership2' ),
		);

		$register_button = array(
			'id' => 'register',
			'type' => MS_Helper_Html::INPUT_TYPE_SUBMIT,
			'value' => $this->data['label_register'],
		);

		/**
		 * The register button can be modified via a custom filter.
		 * Either update the array properties or replace the array with some
		 * HTML string that will be output.
		 *
		 * @since  1.0.1.2
		 * @param  array|string $register_button
		 * @param  array $data
		 */
		$register_button = apply_filters(
			'ms_shortcode_register_button',
			$register_button,
			$this->data
		);

		$title = $this->data['title'];
		ob_start();

		$reg_url = MS_Model_Pages::get_page_url( MS_Model_Pages::MS_PAGE_REGISTER );
		$reg_url = esc_url_raw(
			add_query_arg( 'action', 'register_user', $reg_url )
		);

		// Default WP action hook
		do_action( 'before_signup_form' );
		?>
		<div class="ms-membership-form-wrapper">
			<?php $this->render_errors(); ?>
			<form
				id="ms-shortcode-register-user-form"
				class="form-membership"
				action="<?php echo esc_url( $reg_url ); ?>"
				method="post">

				<?php wp_nonce_field( $this->data['action'] ); ?>
				<?php if ( ! empty( $title ) ) : ?>
					<legend>
						<?php echo $title; ?>
					</legend>
				<?php endif; ?>

				<?php foreach ( $fields as $field ) {
					if ( is_string( $field ) ) {
						MS_Helper_Html::html_element( $field );
					} elseif ( MS_Helper_Html::INPUT_TYPE_HIDDEN == $field['type'] ) {
						MS_Helper_Html::html_element( $field );
					} else {
						?>
						<div class="ms-form-element ms-form-element-<?php echo esc_attr( $field['id'] ); ?>">
							<?php MS_Helper_Html::html_element( $field ); ?>
						</div>
						<?php
					}
				}

				echo '<div class="ms-extra-fields">';

				/**
				 * Trigger default WordPress action to allow other plugins
				 * to add custom fields to the registration form.
				 *
				 * signup_extra_fields Defined in wp-signup.php which is used
				 *              for Multisite signup process.
				 *
				 * register_form Defined in wp-login.php which is only used for
				 *              Single site registration process.
				 *
				 * @since  1.0.0
				 */
				if ( is_multisite() ) {
					$empty_error = new WP_Error();
					do_action( 'signup_extra_fields', $empty_error );
				} else {
					do_action( 'register_form' ); // Always on the register form.
				}

				echo '</div>';

				MS_Helper_Html::html_element( $register_button );

				if ( is_wp_error( $this->error ) ) {
					/**
					 * Display registration errors.
					 *
					 * @since  1.0.0
					 */
					do_action( 'registration_errors', $this->error );
				}

				/**
				 * This hook is intended to output hidden fields or JS code
				 * at the end of the form tag.
				 *
				 * @since  1.0.1.0
				 */
				do_action( 'ms_shortcode_register_form_end', $this );
				?>
			</form>
			<?php
			if ( $this->data['loginlink'] ) {
				MS_Helper_Html::html_link( $login_link );
			}
			?>
		</div>
		<?php
		// Default WP action hook.

		// Intentionally removed, because this hook should be only used in the
		// blog-signup form. Not during user registration.
		//do_action( 'signup_blogform', array() );

		do_action( 'after_signup_form' );

		$html = ob_get_clean();
		$html = apply_filters( 'ms_compact_code', $html );

		return apply_filters(
			'ms_shortcode_register',
			$html,
			$this->data
		);
	}

	/**
	 * Prepares the fields that are displayed in the form.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function prepare_fields() {
		$data = $this->data;

		$fields = array(
			'membership_id' => array(
				'id' => 'membership_id',
				'type' => MS_Helper_Html::INPUT_TYPE_HIDDEN,
				'value' => $data['membership_id'],
			),

			'first_name' => array(
				'id' => 'first_name',
				'title' => $data['label_first_name'],
				'placeholder' => $data['hint_first_name'],
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'value' => $data['first_name'],
			),

			'last_name' => array(
				'id' => 'last_name',
				'title' => $data['label_last_name'],
				'placeholder' => $data['hint_last_name'],
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'value' => $data['last_name'],
			),

			'username' => array(
				'id' => 'username',
				'title' => $data['label_username'],
				'placeholder' => $data['hint_username'],
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'value' => $data['username'],
			),

			'email' => array(
				'id' => 'email',
				'title' => $data['label_email'],
				'placeholder' => $data['hint_email'],
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'value' => $data['email'],
			),

			'password' => array(
				'id' => 'password',
				'title' => $data['label_password'],
				'placeholder' => $data['hint_password'],
				'type' => MS_Helper_Html::INPUT_TYPE_PASSWORD,
				'value' => '',
			),

			'password2' => array(
				'id' => 'password2',
				'title' => $data['label_password2'],
				'placeholder' => $data['hint_password2'],
				'type' => MS_Helper_Html::INPUT_TYPE_PASSWORD,
				'value' => '',
			),

			'action' => array(
				'id' => 'action',
				'type' => MS_Helper_Html::INPUT_TYPE_HIDDEN,
				'value' => $data['action'],
			),

			'step' => array(
				'id' => 'step',
				'type' => MS_Helper_Html::INPUT_TYPE_HIDDEN,
				'value' => $data['step'],
			),
		);

		return apply_filters(
			'ms_shortcode_register_form_fields',
			$fields,
			$this
		);
	}

	/**
	 * Outputs the javascript used by the registration form.
	 *
	 * @since 1.0.1.0
	 */
	static public function add_scripts() {
		static $Scripts_Done = false;

		// Make sure to only execute that function once.
		if ( $Scripts_Done ) { return; }
		$Scripts_Done = true;

		$rule_data = array(
			'username' => array(
				'required' => true,
			),
			'password' => array(
				'required' => true,
				'minlength' => 5,
			),
			'password2' => array(
				'required' => true,
				'equalTo' => '#password',
			),
		);

		/**
		 * Allow other plugins or Add-ons to modify the validation rules on the
		 * registration page.
		 *
		 * @since  1.0.1.0
		 * @var  array
		 */
		$rule_data = apply_filters(
			'ms_shortcode_register_form_rules',
			$rule_data
		);

		ob_start();
		?>
		jQuery(function() {
		var args = {
			onkeyup: false,
			errorClass: 'ms-validation-error',
			rules: <?php echo json_encode( $rule_data ); ?>
		};

		jQuery( '#ms-shortcode-register-user-form' ).validate( args );
		});
		<?php
		$script = ob_get_clean();
		lib3()->ui->script( $script );
	}

	/**
	 * Renders error messages.
	 *
	 * @since  1.0.0
	 * @internal
	 */
	protected function render_errors() {
		$errors = MS_Controller_Frontend::$register_errors;

		if ( ! empty( $errors ) ) {
			?>
			<div class="ms-alert-box ms-alert-error">
				<?php echo $errors; ?>
			</div>
			<?php
		}
	}

}