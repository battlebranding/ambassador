<?php

class WC_Gateway_Stripe extends WC_Payment_Gateway {

	function __construct() {

		$this->id = 'stripe';
		$this->icon = '';
		$this->has_fields = false;
		$this->method_title = 'Stripe';
		$this->method_description = '';

		$this->init_form_fields();
		$this->init_settings();

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

	}

	function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
				'title' => __( 'Enable/Disable', 'woocommerce' ),
				'type' => 'checkbox',
				'label' => __( 'Enable Credit/Debit Card Payments using Stripe', 'woocommerce' ),
				'default' => 'no'
			),
			'stripe_connect' => array(
				'title' => __( 'Connect Stripe Account', 'woocommerce' ),
				'type' => 'stripe_connect',
				'default' => ''
			)
		);

	}

	function process_payment( $order_id ) {

		global $woocommerce;

		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status('on-hold', __( 'Awaiting cheque payment', 'woocommerce' ));

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		$woocommerce->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);

	}

	function generate_stripe_connect_html( $key, $data ) {

		$stripe_options = get_option( 'stripe' );
		$stripe_mode = isset( $stripe_options['mode'] ) ? $stripe_options['mode'] : 'development';

		$connect_index = 'connect_' . $stripe_mode . '_client_id';
		$connect_key = $stripe_options[ $connect_index ];

		$stripe_tokens = get_option( 'stripe_tokens' );

		$field    = $this->get_field_key( $key );
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<?php if ( $stripe_tokens ): ?>
						<p>You account is connected! You are ready to accept payments via Stripe. If for some reason you need to, <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=<?php echo $connect_key; ?>&scope=read_write">click here</a> to recconect your account.</p>
					<?php else: ?>
						<p><a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=<?php echo $connect_key; ?>&scope=read_write"><img src="<?php echo get_stylesheet_directory_uri(); ?>/includes/extensions/woo-stripe/assets/images/blue-on-light.png" alt="Connect With Stripe" ></a></p>
					<?php endif; ?>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();

	}

}

class Ambassador_Theme_Woo_Stripe {

	protected static $single_instance = null;

	static function init() {

		if ( self::$single_instance === null ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;

	}

	public function hooks() {
		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_stripe_gateway_class' ) );
		add_action( 'parse_request', array( $this, 'authorize_stripe_account' ) );
	}

	public function add_stripe_gateway_class( $methods ) {

		$methods[] = 'WC_Gateway_Stripe';

		return $methods;

	}

	public function authorize_stripe_account( $request ) {

		$stripe_options = get_option( 'stripe' );
		$stripe_mode = isset( $stripe_options['mode'] ) ? $stripe_options['mode'] : 'development';

		$secret_key_index = $stripe_mode . '_secret_key';
		$secret_key = $stripe_options[ $secret_key_index ];

		$authorize_stripe = ( isset( $_REQUEST['authorize_stripe'] ) && 'true' == $_REQUEST['authorize_stripe'] ) ? true : false;
		$authorization_code = isset( $_REQUEST['code'] ) ? $_REQUEST['code'] : '';

		$url = 'https://connect.stripe.com/oauth/token';
		$args = array(
			'body' => array(
				'client_secret' => $secret_key,
				'code' => $authorization_code,
				'grant_type' => 'authorization_code'
			)
		);

		$response = wp_remote_post( $url, $args );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( 200 == $response_code ) {

			$data = json_decode( $response_body );

			update_option( 'stripe_tokens', $data );

			wp_redirect( admin_url('admin.php?page=wc-settings&tab=checkout&section=wc_gateway_stripe'), 301 );
			exit;

		}

	}

}

add_action( 'after_setup_theme', array( Ambassador_Theme_Woo_Stripe::init(), 'hooks' ) );