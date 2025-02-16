<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Gateway_Scapa extends WC_Payment_Gateway_CC {

	/**
	 * Is test mode active?
	 *
	 * @var bool
	 */
	public $testmode;

	/**
	 * Should orders be marked as complete after payment?
	 * 
	 * @var bool
	 */
	public $autocomplete_order;

	/**
	 * Scapa payment page type.
	 *
	 * @var string
	 */
	public $payment_page;

	/**
	 * Scapa test public key.
	 *
	 * @var string
	 */
	public $test_public_key;

	/**
	 * Scapa test secret key.
	 *
	 * @var string
	 */
	public $test_secret_key;

	/**
	 * Scapa live public key.
	 *
	 * @var string
	 */
	public $live_public_key;

	/**
	 * Scapa live secret key.
	 *
	 * @var string
	 */
	public $live_secret_key;

	/**
	 * Should we save customer cards?
	 *
	 * @var bool
	 */
	public $saved_cards;

	/**
	 * Should Scapa split payment be enabled.
	 *
	 * @var bool
	 */
	public $split_payment;

	/**
	 * Should the cancel & remove order button be removed on the pay for order page.
	 *
	 * @var bool
	 */
	public $remove_cancel_order_button;

	/**
	 * Scapa sub account code.
	 *
	 * @var string
	 */
	public $subaccount_code;

	/**
	 * Who bears Scapa charges?
	 *
	 * @var string
	 */
	public $charges_account;

	/**
	 * A flat fee to charge the sub account for each transaction.
	 *
	 * @var string
	 */
	public $transaction_charges;

	/**
	 * Should custom metadata be enabled?
	 *
	 * @var bool
	 */
	public $custom_metadata;

	/**
	 * Should the order id be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_order_id;

	/**
	 * Should the customer name be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_name;

	/**
	 * Should the billing email be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_email;

	/**
	 * Should the billing phone be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_phone;

	/**
	 * Should the billing address be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_billing_address;

	/**
	 * Should the shipping address be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_shipping_address;

	/**
	 * Should the order items be sent as a custom metadata to Scapa?
	 *
	 * @var bool
	 */
	public $meta_products;

	/**
	 * API public key
	 *
	 * @var string
	 */
	public $public_key;

	/**
	 * API secret key
	 *
	 * @var string
	 */
	public $secret_key;

	/**
	 * Gateway disabled message
	 *
	 * @var string
	 */
	public $msg;

	/**
	 * Payment channels.
	 *
	 * @var array
	 */
	public $payment_channels = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                 = 'scapa';
		$this->method_title       = __( 'Scapa', 'woo-scapa' );
		$this->method_description = sprintf( __( 'Scapa provide merchants with the tools and services needed to accept online payments from local and international customers using Mastercard, Visa, Verve Cards and Bank Accounts. <a href="%1$s" target="_blank">Sign up</a> for a Scapa account, and <a href="%2$s" target="_blank">get your API keys</a>.', 'woo-scapa' ), 'https://scapa.io', 'https://dashboard.scapa.io/#/settings/developer' );
		$this->has_fields         = true;

		$this->payment_page = $this->get_option( 'payment_page' );

		$this->supports = array(
			'products',
			'refunds',
			'tokenization',
			'subscriptions',
			'multiple_subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer',
		);

		// Load the form fields
		$this->init_form_fields();

		// Load the settings
		$this->init_settings();

		// Get setting values

		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' );
		$this->enabled            = $this->get_option( 'enabled' );
		$this->testmode           = $this->get_option( 'testmode' ) === 'yes' ? true : false;
		$this->autocomplete_order = $this->get_option( 'autocomplete_order' ) === 'yes' ? true : false;

		$this->test_public_key = $this->get_option( 'test_public_key' );
		$this->test_secret_key = $this->get_option( 'test_secret_key' );

		$this->live_public_key = $this->get_option( 'live_public_key' );
		$this->live_secret_key = $this->get_option( 'live_secret_key' );

		$this->saved_cards = $this->get_option( 'saved_cards' ) === 'yes' ? true : false;

		$this->split_payment              = $this->get_option( 'split_payment' ) === 'yes' ? true : false;
		$this->remove_cancel_order_button = $this->get_option( 'remove_cancel_order_button' ) === 'yes' ? true : false;
		$this->subaccount_code            = $this->get_option( 'subaccount_code' );
		$this->charges_account            = $this->get_option( 'split_payment_charge_account' );
		$this->transaction_charges        = $this->get_option( 'split_payment_transaction_charge' );

		$this->custom_metadata = $this->get_option( 'custom_metadata' ) === 'yes' ? true : false;

		$this->meta_order_id         = $this->get_option( 'meta_order_id' ) === 'yes' ? true : false;
		$this->meta_name             = $this->get_option( 'meta_name' ) === 'yes' ? true : false;
		$this->meta_email            = $this->get_option( 'meta_email' ) === 'yes' ? true : false;
		$this->meta_phone            = $this->get_option( 'meta_phone' ) === 'yes' ? true : false;
		$this->meta_billing_address  = $this->get_option( 'meta_billing_address' ) === 'yes' ? true : false;
		$this->meta_shipping_address = $this->get_option( 'meta_shipping_address' ) === 'yes' ? true : false;
		$this->meta_products         = $this->get_option( 'meta_products' ) === 'yes' ? true : false;

		$this->public_key = $this->testmode ? $this->test_public_key : $this->live_public_key;
		$this->secret_key = $this->testmode ? $this->test_secret_key : $this->live_secret_key;

		// Hooks
		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->id,
			array(
				$this,
				'process_admin_options',
			)
		);

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );

		// Payment listener/API hook.
		add_action( 'woocommerce_api_wc_gateway_scapa', array( $this, 'verify_scapa_transaction' ) );

		// Webhook listener/API hook.
		add_action( 'woocommerce_api_tbz_wc_scapa_webhook', array( $this, 'process_webhooks' ) );

		// Check if the gateway can be used.
		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = false;
		}

	}

	/**
	 * Check if this gateway is enabled and available in the user's country.
	 */
	public function is_valid_for_use() {

		if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_scapa_supported_currencies', array( 'NGN', 'USD', 'ZAR', 'GHS', 'KES', 'XOF', 'EGP', 'RWF' ) ) ) ) {

			$this->msg = sprintf( __( 'Scapa does not support your store currency. Kindly set it to either NGN (&#8358), GHS (&#x20b5;), USD (&#36;), KES (KSh), RWF (R₣), ZAR (R), XOF (CFA), or EGP (E£) <a href="%s">here</a>', 'woo-scapa' ), admin_url( 'admin.php?page=wc-settings&tab=general' ) );

			return false;

		}

		return true;

	}

	/**
	 * Display scapa payment icon.
	 */
	public function get_icon() {

		$base_location = wc_get_base_location();

		if ( 'GH' === $base_location['country'] ) {
			$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-gh.png', WC_SCAPA_MAIN_FILE ) ) . '" alt="Scapa Payment Options" />';
		} elseif ( 'ZA' === $base_location['country'] ) {
			$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-za.png', WC_SCAPA_MAIN_FILE ) ) . '" alt="Scapa Payment Options" />';
		} elseif ( 'KE' === $base_location['country'] ) {
			$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-ke.png', WC_SCAPA_MAIN_FILE ) ) . '" alt="Scapa Payment Options" />';
		} elseif ( 'CI' === $base_location['country'] ) {
			$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-civ.png', WC_SCAPA_MAIN_FILE ) ) . '" alt="Scapa Payment Options" />';
		} else {
			$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-wc.png', WC_SCAPA_MAIN_FILE ) ) . '" alt="Scapa Payment Options" />';
		}

		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );

	}

	/**
	 * Check if Scapa merchant details is filled.
	 */
	public function admin_notices() {

		if ( $this->enabled == 'no' ) {
			return;
		}

		// Check required fields.
		if ( ! ( $this->public_key && $this->secret_key ) ) {
			echo '<div class="error"><p>' . sprintf( __( 'Please enter your Scapa merchant details <a href="%s">here</a> to be able to use the Scapa WooCommerce plugin.', 'woo-scapa' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=scapa' ) ) . '</p></div>';
			return;
		}

	}

	/**
	 * Check if Scapa gateway is enabled.
	 *
	 * @return bool
	 */
	public function is_available() {

		if ( 'yes' == $this->enabled ) {

			if ( ! ( $this->public_key && $this->secret_key ) ) {

				return false;

			}

			return true;

		}

		return false;

	}

	/**
	 * Admin Panel Options.
	 */
	public function admin_options() {

		?>

		<h2><?php _e( 'Scapa', 'woo-scapa' ); ?>
		<?php
		if ( function_exists( 'wc_back_link' ) ) {
			wc_back_link( __( 'Return to payments', 'woo-scapa' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
		}
		?>
		</h2>

		<h4>
			<strong><?php printf( __( 'Optional: To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="%1$s" target="_blank" rel="noopener noreferrer">here</a> to the URL below<span style="color: red"><pre><code>%2$s</code></pre></span>', 'woo-scapa' ), 'https://dashboard.scapa.io/#/settings/developer', WC()->api_request_url( 'Tbz_WC_Scapa_Webhook' ) ); ?></strong>
		</h4>

		<?php

		if ( $this->is_valid_for_use() ) {

			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';

		} else {
			?>
			<div class="inline error"><p><strong><?php _e( 'Scapa Payment Gateway Disabled', 'woo-scapa' ); ?></strong>: <?php echo $this->msg; ?></p></div>

			<?php
		}

	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

		$form_fields = array(
			'enabled'                          => array(
				'title'       => __( 'Enable/Disable', 'woo-scapa' ),
				'label'       => __( 'Enable Scapa', 'woo-scapa' ),
				'type'        => 'checkbox',
				'description' => __( 'Enable Scapa as a payment option on the checkout page.', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'title'                            => array(
				'title'       => __( 'Title', 'woo-scapa' ),
				'type'        => 'text',
				'description' => __( 'This controls the payment method title which the user sees during checkout.', 'woo-scapa' ),
				'default'     => __( 'Debit/Credit Cards', 'woo-scapa' ),
				'desc_tip'    => true,
			),
			'description'                      => array(
				'title'       => __( 'Description', 'woo-scapa' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the payment method description which the user sees during checkout.', 'woo-scapa' ),
				'default'     => __( 'Make payment using your debit and credit cards', 'woo-scapa' ),
				'desc_tip'    => true,
			),
			'testmode'                         => array(
				'title'       => __( 'Test mode', 'woo-scapa' ),
				'label'       => __( 'Enable Test Mode', 'woo-scapa' ),
				'type'        => 'checkbox',
				'description' => __( 'Test mode enables you to test payments before going live. <br />Once the LIVE MODE is enabled on your Scapa account uncheck this.', 'woo-scapa' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'payment_page'                     => array(
				'title'       => __( 'Payment Option', 'woo-scapa' ),
				'type'        => 'select',
				'description' => __( 'Popup shows the payment popup on the page while Redirect will redirect the customer to Scapa to make payment.', 'woo-scapa' ),
				'default'     => '',
				'desc_tip'    => false,
				'options'     => array(
					''          => __( 'Select One', 'woo-scapa' ),
					// 'inline'    => __( 'Popup', 'woo-scapa' ),
					'redirect'  => __( 'Redirect', 'woo-scapa' ),
				),
			),
			'test_secret_key'                  => array(
				'title'       => __( 'Test Secret Key', 'woo-scapa' ),
				'type'        => 'password',
				'description' => __( 'Enter your Test Secret Key here', 'woo-scapa' ),
				'default'     => '',
			),
			'test_public_key'                  => array(
				'title'       => __( 'Test Public Key', 'woo-scapa' ),
				'type'        => 'text',
				'description' => __( 'Enter your Test Public Key here.', 'woo-scapa' ),
				'default'     => '',
			),
			'live_secret_key'                  => array(
				'title'       => __( 'Live Secret Key', 'woo-scapa' ),
				'type'        => 'password',
				'description' => __( 'Enter your Live Secret Key here.', 'woo-scapa' ),
				'default'     => '',
			),
			'live_public_key'                  => array(
				'title'       => __( 'Live Public Key', 'woo-scapa' ),
				'type'        => 'text',
				'description' => __( 'Enter your Live Public Key here.', 'woo-scapa' ),
				'default'     => '',
			),
			'autocomplete_order'               => array(
				'title'       => __( 'Autocomplete Order After Payment', 'woo-scapa' ),
				'label'       => __( 'Autocomplete Order', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-autocomplete-order',
				'description' => __( 'If enabled, the order will be marked as complete after successful payment', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'remove_cancel_order_button'       => array(
				'title'       => __( 'Remove Cancel Order & Restore Cart Button', 'woo-scapa' ),
				'label'       => __( 'Remove the cancel order & restore cart button on the pay for order page', 'woo-scapa' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'split_payment'                    => array(
				'title'       => __( 'Split Payment', 'woo-scapa' ),
				'label'       => __( 'Enable Split Payment', 'woo-scapa' ),
				'type'        => 'checkbox',
				'description' => '',
				'class'       => 'woocommerce_scapa_split_payment',
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'subaccount_code'                  => array(
				'title'       => __( 'Subaccount Code', 'woo-scapa' ),
				'type'        => 'text',
				'description' => __( 'Enter the subaccount code here.', 'woo-scapa' ),
				'class'       => 'woocommerce_scapa_subaccount_code',
				'default'     => '',
			),
			'split_payment_transaction_charge' => array(
				'title'             => __( 'Split Payment Transaction Charge', 'woo-scapa' ),
				'type'              => 'number',
				'description'       => __( 'A flat fee to charge the subaccount for this transaction, in Naira (&#8358;). This overrides the split percentage set when the subaccount was created. Ideally, you will need to use this if you are splitting in flat rates (since subaccount creation only allows for percentage split). e.g. 100 for a &#8358;100 flat fee.', 'woo-scapa' ),
				'class'             => __( 'woocommerce_scapa_split_payment_transaction_charge', 'woo-scapa' ),
				'default'           => '',
				'custom_attributes' => array(
					'min'  => 1,
					'step' => 0.1,
				),
				'desc_tip'          => false,
			),
			'split_payment_charge_account'     => array(
				'title'       => __( 'Scapa Charges Bearer', 'woo-scapa' ),
				'type'        => 'select',
				'description' => __( 'Who bears Scapa charges?', 'woo-scapa' ),
				'class'       => 'woocommerce_scapa_split_payment_charge_account',
				'default'     => '',
				'desc_tip'    => false,
				'options'     => array(
					''           => __( 'Select One', 'woo-scapa' ),
					'account'    => __( 'Account', 'woo-scapa' ),
					'subaccount' => __( 'Subaccount', 'woo-scapa' ),
				),
			),
			'custom_gateways'                  => array(
				'title'       => __( 'Additional Scapa Gateways', 'woo-scapa' ),
				'type'        => 'select',
				'description' => __( 'Create additional custom Scapa based gateways. This allows you to create additional Scapa gateways using custom filters. You can create a gateway that accepts only verve cards, a gateway that accepts only bank payment, a gateway that accepts a specific bank issued cards.', 'woo-scapa' ),
				'default'     => '',
				'desc_tip'    => true,
				'options'     => array(
					''  => __( 'Select One', 'woo-scapa' ),
					'1' => __( '1 gateway', 'woo-scapa' ),
					'2' => __( '2 gateways', 'woo-scapa' ),
					'3' => __( '3 gateways', 'woo-scapa' ),
					'4' => __( '4 gateways', 'woo-scapa' ),
					'5' => __( '5 gateways', 'woo-scapa' ),
				),
			),
			'saved_cards'                      => array(
				'title'       => __( 'Saved Cards', 'woo-scapa' ),
				'label'       => __( 'Enable Payment via Saved Cards', 'woo-scapa' ),
				'type'        => 'checkbox',
				'description' => __( 'If enabled, users will be able to pay with a saved card during checkout. Card details are saved on Scapa servers, not on your store.<br>Note that you need to have a valid SSL certificate installed.', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'custom_metadata'                  => array(
				'title'       => __( 'Custom Metadata', 'woo-scapa' ),
				'label'       => __( 'Enable Custom Metadata', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-metadata',
				'description' => __( 'If enabled, you will be able to send more information about the order to Scapa.', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_order_id'                    => array(
				'title'       => __( 'Order ID', 'woo-scapa' ),
				'label'       => __( 'Send Order ID', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-order-id',
				'description' => __( 'If checked, the Order ID will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_name'                        => array(
				'title'       => __( 'Customer Name', 'woo-scapa' ),
				'label'       => __( 'Send Customer Name', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-name',
				'description' => __( 'If checked, the customer full name will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_email'                       => array(
				'title'       => __( 'Customer Email', 'woo-scapa' ),
				'label'       => __( 'Send Customer Email', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-email',
				'description' => __( 'If checked, the customer email address will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_phone'                       => array(
				'title'       => __( 'Customer Phone', 'woo-scapa' ),
				'label'       => __( 'Send Customer Phone', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-phone',
				'description' => __( 'If checked, the customer phone will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_billing_address'             => array(
				'title'       => __( 'Order Billing Address', 'woo-scapa' ),
				'label'       => __( 'Send Order Billing Address', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-billing-address',
				'description' => __( 'If checked, the order billing address will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_shipping_address'            => array(
				'title'       => __( 'Order Shipping Address', 'woo-scapa' ),
				'label'       => __( 'Send Order Shipping Address', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-shipping-address',
				'description' => __( 'If checked, the order shipping address will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'meta_products'                    => array(
				'title'       => __( 'Product(s) Purchased', 'woo-scapa' ),
				'label'       => __( 'Send Product(s) Purchased', 'woo-scapa' ),
				'type'        => 'checkbox',
				'class'       => 'wc-scapa-meta-products',
				'description' => __( 'If checked, the product(s) purchased will be sent to Scapa', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
		);

		if ( 'NGN' !== get_woocommerce_currency() ) {
			unset( $form_fields['custom_gateways'] );
		}

		$this->form_fields = $form_fields;

	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {

		if ( $this->description ) {
			echo wpautop( wptexturize( $this->description ) );
		}

		if ( ! is_ssl() ) {
			return;
		}

		if ( $this->supports( 'tokenization' ) && is_checkout() && $this->saved_cards && is_user_logged_in() ) {
			$this->tokenization_script();
			$this->saved_payment_methods();
			$this->save_payment_method_checkbox();
		}

	}

	/**
	 * Outputs scripts used for scapa payment.
	 */
	public function payment_scripts() {

		if ( isset( $_GET['pay_for_order'] ) || ! is_checkout_pay_page() ) {
			return;
		}

		if ( $this->enabled === 'no' ) {
			return;
		}

		$order_key = urldecode( $_GET['key'] );
		$order_id  = absint( get_query_var( 'order-pay' ) );

		$order = wc_get_order( $order_id );

		if ( $this->id !== $order->get_payment_method() ) {
			return;
		}

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'scapa', 'https://js.scapa.io/v2/inline.js', array( 'jquery' ), WC_SCAPA_VERSION, false );

		wp_enqueue_script( 'wc_scapa', plugins_url( 'assets/js/scapa' . $suffix . '.js', WC_SCAPA_MAIN_FILE ), array( 'jquery', 'scapa' ), WC_SCAPA_VERSION, false );

		$scapa_params = array(
			'key' => $this->public_key,
		);

		if ( is_checkout_pay_page() && get_query_var( 'order-pay' ) ) {

			$email         = $order->get_billing_email();
			$amount        = $order->get_total() * 100;
			$txnref        = $order_id . '_' . time();
			$the_order_id  = $order->get_id();
			$the_order_key = $order->get_order_key();
			$currency      = $order->get_currency();

			if ( $the_order_id == $order_id && $the_order_key == $order_key ) {

				$scapa_params['email']    = $email;
				$scapa_params['amount']   = absint( $amount );
				$scapa_params['txnref']   = $txnref;
				$scapa_params['currency'] = $currency;

			}

			if ( $this->split_payment ) {

				$scapa_params['subaccount_code'] = $this->subaccount_code;
				$scapa_params['charges_account'] = $this->charges_account;

				if ( empty( $this->transaction_charges ) ) {
					$scapa_params['transaction_charges'] = '';
				} else {
					$scapa_params['transaction_charges'] = $this->transaction_charges * 100;
				}
			}

			if ( $this->custom_metadata ) {

				if ( $this->meta_order_id ) {

					$scapa_params['meta_order_id'] = $order_id;

				}

				if ( $this->meta_name ) {

					$scapa_params['meta_name'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

				}

				if ( $this->meta_email ) {

					$scapa_params['meta_email'] = $email;

				}

				if ( $this->meta_phone ) {

					$scapa_params['meta_phone'] = $order->get_billing_phone();

				}

				if ( $this->meta_products ) {

					$line_items = $order->get_items();

					$products = '';

					foreach ( $line_items as $item_id => $item ) {
						$name      = $item['name'];
						$quantity  = $item['qty'];
						$products .= $name . ' (Qty: ' . $quantity . ')';
						$products .= ' | ';
					}

					$products = rtrim( $products, ' | ' );

					$scapa_params['meta_products'] = $products;

				}

				if ( $this->meta_billing_address ) {

					$billing_address = $order->get_formatted_billing_address();
					$billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

					$scapa_params['meta_billing_address'] = $billing_address;

				}

				if ( $this->meta_shipping_address ) {

					$shipping_address = $order->get_formatted_shipping_address();
					$shipping_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $shipping_address ) );

					if ( empty( $shipping_address ) ) {

						$billing_address = $order->get_formatted_billing_address();
						$billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

						$shipping_address = $billing_address;

					}

					$scapa_params['meta_shipping_address'] = $shipping_address;

				}
			}

			$order->update_meta_data( '_scapa_txn_ref', $txnref );
			$order->save();
		}

		$payment_channels = $this->get_gateway_payment_channels( $order );

		if ( ! empty( $payment_channels ) ) {
			if ( in_array( 'card', $payment_channels, true ) ) {
				$scapa_params['card_channel'] = 'true';
			}

			if ( in_array( 'bank', $payment_channels, true ) ) {
				$scapa_params['bank_channel'] = 'true';
			}

			if ( in_array( 'ussd', $payment_channels, true ) ) {
				$scapa_params['ussd_channel'] = 'true';
			}

			if ( in_array( 'qr', $payment_channels, true ) ) {
				$scapa_params['qr_channel'] = 'true';
			}

			if ( in_array( 'bank_transfer', $payment_channels, true ) ) {
				$scapa_params['bank_transfer_channel'] = 'true';
			}
		}

		wp_localize_script( 'wc_scapa', 'wc_scapa_params', $scapa_params );

	}

	/**
	 * Load admin scripts.
	 */
	public function admin_scripts() {

		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$scapa_admin_params = array(
			'plugin_url' => WC_SCAPA_URL,
		);

		wp_enqueue_script( 'wc_scapa_admin', plugins_url( 'assets/js/scapa-admin' . $suffix . '.js', WC_SCAPA_MAIN_FILE ), array(), WC_SCAPA_VERSION, true );

		wp_localize_script( 'wc_scapa_admin', 'wc_scapa_admin_params', $scapa_admin_params );

	}

	/**
	 * Process the payment.
	 *
	 * @param int $order_id
	 *
	 * @return array|void
	 */
	public function process_payment( $order_id ) {
		$payment_token = 'wc-' . trim( $this->id ) . '-payment-token';

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST[ $payment_token ] ) && 'new' !== wc_clean( $_POST[ $payment_token ] ) ) {

			// phpcs:ignore WordPress.Security.NonceVerification
			$token_id = wc_clean( $_POST[ $payment_token ] );
			$token    = \WC_Payment_Tokens::get( $token_id );

			if ( $token->get_user_id() !== get_current_user_id() ) {

				wc_add_notice( 'Invalid token ID', 'error' );

				return;
			}

			$token_payment_status = $this->process_token_payment( $token->get_token(), $order_id );

			if ( ! $token_payment_status ) {
				return;
			}

			$order = wc_get_order( $order_id );

			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}

		$order = wc_get_order( $order_id );

		$new_payment_method = 'wc-' . trim( $this->id ) . '-new-payment-method';

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST[ $new_payment_method ] ) && ( true === (bool) $_POST[ $new_payment_method ] && $this->saved_cards ) && is_user_logged_in() ) {

			$order->update_meta_data( '_wc_scapa_save_card', true );

			$order->save();
		}

		if ( 'redirect' === $this->payment_page ) {
			return $this->process_redirect_payment_option( $order_id );
		}

		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true ),
		);
	}

	/**
	 * Process a redirect payment option payment.
	 *
	 * @since 5.7
	 * @param int $order_id
	 * @return array|void
	 */
	public function process_redirect_payment_option( $order_id ) {

		$order        = wc_get_order( $order_id );
		$amount       = $order->get_total() * 100;
		$txnref       = $order_id . '_' . time();
		$callback_url = WC()->api_request_url( 'WC_Gateway_Scapa' );

		$payment_channels = $this->get_gateway_payment_channels( $order );

		$scapa_params = array(
			'amount'       => absint( $amount ),
			'email'        => $order->get_billing_email(),
			'currency'     => $order->get_currency(),
			'reference'    => $txnref,
			'callback_url' => $callback_url,
		);

		if ( ! empty( $payment_channels ) ) {
			$scapa_params['channels'] = $payment_channels;
		}

		if ( $this->split_payment ) {

			$scapa_params['subaccount'] = $this->subaccount_code;
			$scapa_params['bearer']     = $this->charges_account;

			if ( empty( $this->transaction_charges ) ) {
				$scapa_params['transaction_charge'] = '';
			} else {
				$scapa_params['transaction_charge'] = $this->transaction_charges * 100;
			}
		}

		$scapa_params['metadata']['custom_fields'] = $this->get_custom_fields( $order_id );
		$scapa_params['metadata']['cancel_action'] = wc_get_cart_url();

		$order->update_meta_data( '_scapa_txn_ref', $txnref );
		$order->save();

		$scapa_url = 'https://api.scapa.io/transaction/initialize/';

		$headers = array(
			'Authorization' => 'Bearer ' . $this->secret_key,
			'Content-Type'  => 'application/json',
		);

		$args = array(
			'headers' => $headers,
			'timeout' => 60,
			'body'    => json_encode( $scapa_params ),
		);

		$request = wp_remote_post( $scapa_url, $args );

		if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {

			$scapa_response = json_decode( wp_remote_retrieve_body( $request ) );

			return array(
				'result'   => 'success',
				'redirect' => $scapa_response->data->authorization_url,
			);

		} else {
			wc_add_notice( __( 'Unable to process payment try again', 'woo-scapa' ), 'error' );

			return;
		}

	}

	/**
	 * Process a token payment.
	 *
	 * @param $token
	 * @param $order_id
	 *
	 * @return bool
	 */
	public function process_token_payment( $token, $order_id ) {

		if ( $token && $order_id ) {

			$order = wc_get_order( $order_id );

			$order_amount = $order->get_total() * 100;
			$txnref       = $order_id . '_' . time();

			$order->update_meta_data( '_scapa_txn_ref', $txnref );
			$order->save();

			$scapa_url = 'https://api.scapa.io/transaction/charge_authorization';

			$headers = array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $this->secret_key,
			);

			$metadata['custom_fields'] = $this->get_custom_fields( $order_id );

			if ( strpos( $token, '###' ) !== false ) {
				$payment_token  = explode( '###', $token );
				$auth_code      = $payment_token[0];
				$customer_email = $payment_token[1];
			} else {
				$auth_code      = $token;
				$customer_email = $order->get_billing_email();
			}

			$body = array(
				'email'              => $customer_email,
				'amount'             => absint( $order_amount ),
				'metadata'           => $metadata,
				'authorization_code' => $auth_code,
				'reference'          => $txnref,
				'currency'           => $order->get_currency(),
			);

			$args = array(
				'body'    => json_encode( $body ),
				'headers' => $headers,
				'timeout' => 60,
			);

			$request = wp_remote_post( $scapa_url, $args );

			$response_code = wp_remote_retrieve_response_code( $request );

			if ( ! is_wp_error( $request ) && in_array( $response_code, array( 200, 400 ), true ) ) {

				$scapa_response = json_decode( wp_remote_retrieve_body( $request ) );

				if ( ( 200 === $response_code ) && ( 'success' === strtolower( $scapa_response->data->status ) ) ) {

					$order = wc_get_order( $order_id );

					if ( in_array( $order->get_status(), array( 'processing', 'completed', 'on-hold' ) ) ) {

						wp_redirect( $this->get_return_url( $order ) );

						exit;

					}

					$order_total      = $order->get_total();
					$order_currency   = $order->get_currency();
					$currency_symbol  = get_woocommerce_currency_symbol( $order_currency );
					$amount_paid      = $scapa_response->data->amount / 100;
					$scapa_ref     = $scapa_response->data->reference;
					$payment_currency = $scapa_response->data->currency;
					$gateway_symbol   = get_woocommerce_currency_symbol( $payment_currency );

					// check if the amount paid is equal to the order amount.
					if ( $amount_paid < absint( $order_total ) ) {

						$order->update_status( 'on-hold', '' );

						$order->add_meta_data( '_transaction_id', $scapa_ref, true );

						$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-scapa' ), '<br />', '<br />', '<br />' );
						$notice_type = 'notice';

						// Add Customer Order Note
						$order->add_order_note( $notice, 1 );

						// Add Admin Order Note
						$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Scapa Transaction Reference:</strong> %9$s', 'woo-scapa' ), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $scapa_ref );
						$order->add_order_note( $admin_order_note );

						wc_add_notice( $notice, $notice_type );

					} else {

						if ( $payment_currency !== $order_currency ) {

							$order->update_status( 'on-hold', '' );

							$order->update_meta_data( '_transaction_id', $scapa_ref );

							$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-scapa' ), '<br />', '<br />', '<br />' );
							$notice_type = 'notice';

							// Add Customer Order Note
							$order->add_order_note( $notice, 1 );

							// Add Admin Order Note
							$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Scapa Transaction Reference:</strong> %9$s', 'woo-scapa' ), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $scapa_ref );
							$order->add_order_note( $admin_order_note );

							function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

							wc_add_notice( $notice, $notice_type );

						} else {

							$order->payment_complete( $scapa_ref );

							$order->add_order_note( sprintf( 'Payment via Scapa successful (Transaction Reference: %s)', $scapa_ref ) );

							if ( $this->is_autocomplete_order_enabled( $order ) ) {
								$order->update_status( 'completed' );
							}
						}
					}

					$order->save();

					$this->save_subscription_payment_token( $order_id, $scapa_response );

					WC()->cart->empty_cart();

					return true;

				} else {

					$order_notice  = __( 'Payment was declined by Scapa.', 'woo-scapa' );
					$failed_notice = __( 'Payment failed using the saved card. Kindly use another payment option.', 'woo-scapa' );

					if ( ! empty( $scapa_response->message ) ) {

						$order_notice  = sprintf( __( 'Payment was declined by Scapa. Reason: %s.', 'woo-scapa' ), $scapa_response->message );
						$failed_notice = sprintf( __( 'Payment failed using the saved card. Reason: %s. Kindly use another payment option.', 'woo-scapa' ), $scapa_response->message );

					}

					$order->update_status( 'failed', $order_notice );

					wc_add_notice( $failed_notice, 'error' );

					do_action( 'wc_gateway_scapa_process_payment_error', $failed_notice, $order );

					return false;
				}
			}
		} else {

			wc_add_notice( __( 'Payment Failed.', 'woo-scapa' ), 'error' );

			return false;
		}

	}

	/**
	 * Show new card can only be added when placing an order notice.
	 */
	public function add_payment_method() {

		wc_add_notice( __( 'You can only add a new card when placing an order.', 'woo-scapa' ), 'error' );

		return;

	}

	/**
	 * Displays the payment page.
	 *
	 * @param $order_id
	 */
	public function receipt_page( $order_id ) {

		$order = wc_get_order( $order_id );

		echo '<div id="wc-scapa-form">';

		echo '<p>' . __( 'Thank you for your order, please click the button below to pay with Scapa.', 'woo-scapa' ) . '</p>';

		echo '<div id="scapa_form"><form id="order_review" method="post" action="' . WC()->api_request_url( 'WC_Gateway_Scapa' ) . '"></form><button class="button" id="scapa-payment-button">' . __( 'Pay Now', 'woo-scapa' ) . '</button>';

		if ( ! $this->remove_cancel_order_button ) {
			echo '  <a class="button cancel" id="scapa-cancel-payment-button" href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'Cancel order &amp; restore cart', 'woo-scapa' ) . '</a></div>';
		}

		echo '</div>';

	}

	/**
	 * Verify Scapa payment.
	 */
	public function verify_scapa_transaction() {

		if ( isset( $_REQUEST['scapa_txnref'] ) ) {
			$scapa_txn_ref = sanitize_text_field( $_REQUEST['scapa_txnref'] );
		} elseif ( isset( $_REQUEST['reference'] ) ) {
			$scapa_txn_ref = sanitize_text_field( $_REQUEST['reference'] );
		} else {
			$scapa_txn_ref = false;
		}

		@ob_clean();

		if ( $scapa_txn_ref ) {

            $scapa_response = $this->get_scapa_transaction( $scapa_txn_ref );

			if ( false !== $scapa_response ) {

				if ( 'success' == $scapa_response->data->status ) {

					$order_details = explode( '_', $scapa_response->data->reference );
					$order_id      = (int) $order_details[0];
					$order         = wc_get_order( $order_id );

					if ( in_array( $order->get_status(), array( 'processing', 'completed', 'on-hold' ) ) ) {

						wp_redirect( $this->get_return_url( $order ) );

						exit;

					}

					$order_total      = $order->get_total();
					$order_currency   = $order->get_currency();
					$currency_symbol  = get_woocommerce_currency_symbol( $order_currency );
					$amount_paid      = $scapa_response->data->amount / 100;
					$scapa_ref     = $scapa_response->data->reference;
					$payment_currency = strtoupper( $scapa_response->data->currency );
					$gateway_symbol   = get_woocommerce_currency_symbol( $payment_currency );

					// check if the amount paid is equal to the order amount.
					if ( $amount_paid < absint( $order_total ) ) {

						$order->update_status( 'on-hold', '' );

						$order->add_meta_data( '_transaction_id', $scapa_ref, true );

						$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-scapa' ), '<br />', '<br />', '<br />' );
						$notice_type = 'notice';

						// Add Customer Order Note
						$order->add_order_note( $notice, 1 );

						// Add Admin Order Note
						$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Scapa Transaction Reference:</strong> %9$s', 'woo-scapa' ), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $scapa_ref );
						$order->add_order_note( $admin_order_note );

						function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

						wc_add_notice( $notice, $notice_type );

					} else {

						if ( $payment_currency !== $order_currency ) {

							$order->update_status( 'on-hold', '' );

							$order->update_meta_data( '_transaction_id', $scapa_ref );

							$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-scapa' ), '<br />', '<br />', '<br />' );
							$notice_type = 'notice';

							// Add Customer Order Note
							$order->add_order_note( $notice, 1 );

							// Add Admin Order Note
							$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Scapa Transaction Reference:</strong> %9$s', 'woo-scapa' ), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $scapa_ref );
							$order->add_order_note( $admin_order_note );

							function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

							wc_add_notice( $notice, $notice_type );

						} else {

							$order->payment_complete( $scapa_ref );
							$order->add_order_note( sprintf( __( 'Payment via Scapa successful (Transaction Reference: %s)', 'woo-scapa' ), $scapa_ref ) );

							if ( $this->is_autocomplete_order_enabled( $order ) ) {
								$order->update_status( 'completed' );
							}
						}
					}

					$order->save();

					$this->save_card_details( $scapa_response, $order->get_user_id(), $order_id );

					WC()->cart->empty_cart();

				} else {

					$order_details = explode( '_', $_REQUEST['scapa_txnref'] );

					$order_id = (int) $order_details[0];

					$order = wc_get_order( $order_id );

					$order->update_status( 'failed', __( 'Payment was declined by Scapa.', 'woo-scapa' ) );

				}
			}

			wp_redirect( $this->get_return_url( $order ) );

			exit;
		}

		wp_redirect( wc_get_page_permalink( 'cart' ) );

		exit;

	}

	/**
	 * Process Webhook.
	 */
	public function process_webhooks() {

		if ( ! array_key_exists( 'HTTP_X_SCAPA_SIGNATURE', $_SERVER ) || ( strtoupper( $_SERVER['REQUEST_METHOD'] ) !== 'POST' ) ) {
			exit;
		}

		$json = file_get_contents( 'php://input' );

		// validate event do all at once to avoid timing attack.
		if ( $_SERVER['HTTP_X_SCAPA_SIGNATURE'] !== hash_hmac( 'sha512', $json, $this->secret_key ) ) {
			exit;
		}

		$event = json_decode( $json );

		if ( 'charge.success' !== strtolower( $event->event ) ) {
			return;
		}

		sleep( 10 );

		$scapa_response = $this->get_scapa_transaction( $event->data->reference );

		if ( false === $scapa_response ) {
			return;
		}

		$order_details = explode( '_', $scapa_response->data->reference );

		$order_id = (int) $order_details[0];

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}

		$scapa_txn_ref = $order->get_meta( '_scapa_txn_ref' );

		if ( $scapa_response->data->reference != $scapa_txn_ref ) {
			exit;
		}

		http_response_code( 200 );

		if ( in_array( strtolower( $order->get_status() ), array( 'processing', 'completed', 'on-hold' ), true ) ) {
			exit;
		}

		$order_currency = $order->get_currency();

		$currency_symbol = get_woocommerce_currency_symbol( $order_currency );

		$order_total = $order->get_total();

		$amount_paid = $scapa_response->data->amount / 100;

		$scapa_ref = $scapa_response->data->reference;

		$payment_currency = strtoupper( $scapa_response->data->currency );

		$gateway_symbol = get_woocommerce_currency_symbol( $payment_currency );

		// check if the amount paid is equal to the order amount.
		if ( $amount_paid < absint( $order_total ) ) {

			$order->update_status( 'on-hold', '' );

			$order->add_meta_data( '_transaction_id', $scapa_ref, true );

			$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-scapa' ), '<br />', '<br />', '<br />' );
			$notice_type = 'notice';

			// Add Customer Order Note.
			$order->add_order_note( $notice, 1 );

			// Add Admin Order Note.
			$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Scapa Transaction Reference:</strong> %9$s', 'woo-scapa' ), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $scapa_ref );
			$order->add_order_note( $admin_order_note );

			function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

			wc_add_notice( $notice, $notice_type );

			WC()->cart->empty_cart();

		} else {

			if ( $payment_currency !== $order_currency ) {

				$order->update_status( 'on-hold', '' );

				$order->update_meta_data( '_transaction_id', $scapa_ref );

				$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-scapa' ), '<br />', '<br />', '<br />' );
				$notice_type = 'notice';

				// Add Customer Order Note.
				$order->add_order_note( $notice, 1 );

				// Add Admin Order Note.
				$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Scapa Transaction Reference:</strong> %9$s', 'woo-scapa' ), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $scapa_ref );
				$order->add_order_note( $admin_order_note );

				function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

				wc_add_notice( $notice, $notice_type );

			} else {

				$order->payment_complete( $scapa_ref );

				$order->add_order_note( sprintf( __( 'Payment via Scapa successful (Transaction Reference: %s)', 'woo-scapa' ), $scapa_ref ) );

				WC()->cart->empty_cart();

				if ( $this->is_autocomplete_order_enabled( $order ) ) {
					$order->update_status( 'completed' );
				}
			}
		}

		$order->save();

		$this->save_card_details( $scapa_response, $order->get_user_id(), $order_id );

		exit;
	}

	/**
	 * Save Customer Card Details.
	 *
	 * @param $scapa_response
	 * @param $user_id
	 * @param $order_id
	 */
	public function save_card_details( $scapa_response, $user_id, $order_id ) {

		$this->save_subscription_payment_token( $order_id, $scapa_response );

		$order = wc_get_order( $order_id );

		$save_card = $order->get_meta( '_wc_scapa_save_card' );

		if ( $user_id && $this->saved_cards && $save_card && $scapa_response->data->authorization->reusable && 'card' == $scapa_response->data->authorization->channel ) {

			$gateway_id = $order->get_payment_method();

			$last4          = $scapa_response->data->authorization->last4;
			$exp_year       = $scapa_response->data->authorization->exp_year;
			$brand          = $scapa_response->data->authorization->card_type;
			$exp_month      = $scapa_response->data->authorization->exp_month;
			$auth_code      = $scapa_response->data->authorization->authorization_code;
			$customer_email = $scapa_response->data->customer->email;

			$payment_token = "$auth_code###$customer_email";

			$token = new WC_Payment_Token_CC();
			$token->set_token( $payment_token );
			$token->set_gateway_id( $gateway_id );
			$token->set_card_type( strtolower( $brand ) );
			$token->set_last4( $last4 );
			$token->set_expiry_month( $exp_month );
			$token->set_expiry_year( $exp_year );
			$token->set_user_id( $user_id );
			$token->save();

			$order->delete_meta_data( '_wc_scapa_save_card' );
			$order->save();
		}
	}

	/**
	 * Save payment token to the order for automatic renewal for further subscription payment.
	 *
	 * @param $order_id
	 * @param $scapa_response
	 */
	public function save_subscription_payment_token( $order_id, $scapa_response ) {

		if ( ! function_exists( 'wcs_order_contains_subscription' ) ) {
			return;
		}

		if ( $this->order_contains_subscription( $order_id ) && $scapa_response->data->authorization->reusable && 'card' == $scapa_response->data->authorization->channel ) {

			$auth_code      = $scapa_response->data->authorization->authorization_code;
			$customer_email = $scapa_response->data->customer->email;

			$payment_token = "$auth_code###$customer_email";

			// Also store it on the subscriptions being purchased or paid for in the order
			if ( function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order_id ) ) {

				$subscriptions = wcs_get_subscriptions_for_order( $order_id );

			} elseif ( function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order_id ) ) {

				$subscriptions = wcs_get_subscriptions_for_renewal_order( $order_id );

			} else {

				$subscriptions = array();

			}

			if ( empty( $subscriptions ) ) {
				return;
			}

			foreach ( $subscriptions as $subscription ) {
				$subscription->update_meta_data( '_scapa_token', $payment_token );
				$subscription->save();
			}
		}

	}

	/**
	 * Get custom fields to pass to Scapa.
	 *
	 * @param int $order_id WC Order ID
	 *
	 * @return array
	 */
	public function get_custom_fields( $order_id ) {

		$order = wc_get_order( $order_id );

		$custom_fields = array();

		$custom_fields[] = array(
			'display_name'  => 'Plugin',
			'variable_name' => 'plugin',
			'value'         => 'woo-scapa',
		);
		
		if ( $this->custom_metadata ) {

			if ( $this->meta_order_id ) {

				$custom_fields[] = array(
					'display_name'  => 'Order ID',
					'variable_name' => 'order_id',
					'value'         => $order_id,
				);

			}

			if ( $this->meta_name ) {

				$custom_fields[] = array(
					'display_name'  => 'Customer Name',
					'variable_name' => 'customer_name',
					'value'         => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
				);

			}

			if ( $this->meta_email ) {

				$custom_fields[] = array(
					'display_name'  => 'Customer Email',
					'variable_name' => 'customer_email',
					'value'         => $order->get_billing_email(),
				);

			}

			if ( $this->meta_phone ) {

				$custom_fields[] = array(
					'display_name'  => 'Customer Phone',
					'variable_name' => 'customer_phone',
					'value'         => $order->get_billing_phone(),
				);

			}

			if ( $this->meta_products ) {

				$line_items = $order->get_items();

				$products = '';

				foreach ( $line_items as $item_id => $item ) {
					$name     = $item['name'];
					$quantity = $item['qty'];
					$products .= $name . ' (Qty: ' . $quantity . ')';
					$products .= ' | ';
				}

				$products = rtrim( $products, ' | ' );

				$custom_fields[] = array(
					'display_name'  => 'Products',
					'variable_name' => 'products',
					'value'         => $products,
				);

			}

			if ( $this->meta_billing_address ) {

				$billing_address = $order->get_formatted_billing_address();
				$billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

				$custom_fields[] = array(
					'display_name'  => 'Billing Address',
					'variable_name' => 'billing_address',
					'value'         => $billing_address,
				);

			}

			if ( $this->meta_shipping_address ) {

				$shipping_address = $order->get_formatted_shipping_address();
				$shipping_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $shipping_address ) );

				if ( empty( $shipping_address ) ) {

					$billing_address = $order->get_formatted_billing_address();
					$billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

					$shipping_address = $billing_address;

				}
				$custom_fields[] = array(
					'display_name'  => 'Shipping Address',
					'variable_name' => 'shipping_address',
					'value'         => $shipping_address,
				);

			}

		}

		return $custom_fields;
	}

	/**
	 * Process a refund request from the Order details screen.
	 *
	 * @param int $order_id WC Order ID.
	 * @param float|null $amount Refund Amount.
	 * @param string $reason Refund Reason
	 *
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {

		if ( ! ( $this->public_key && $this->secret_key ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}

		$order_currency = $order->get_currency();
		$transaction_id = $order->get_transaction_id();

        $scapa_response = $this->get_scapa_transaction( $transaction_id );

		if ( false !== $scapa_response ) {

			if ( 'success' == $scapa_response->data->status ) {

				$merchant_note = sprintf( __( 'Refund for Order ID: #%1$s on %2$s', 'woo-scapa' ), $order_id, get_site_url() );

				$body = array(
					'transaction'   => $transaction_id,
					'amount'        => $amount * 100,
					'currency'      => $order_currency,
					'customer_note' => $reason,
					'merchant_note' => $merchant_note,
				);

				$headers = array(
					'Authorization' => 'Bearer ' . $this->secret_key,
				);

				$args = array(
					'headers' => $headers,
					'timeout' => 60,
					'body'    => $body,
				);

				$refund_url = 'https://api.scapa.io/refund';

				$refund_request = wp_remote_post( $refund_url, $args );

				if ( ! is_wp_error( $refund_request ) && 200 === wp_remote_retrieve_response_code( $refund_request ) ) {

					$refund_response = json_decode( wp_remote_retrieve_body( $refund_request ) );

					if ( $refund_response->status ) {
						$amount         = wc_price( $amount, array( 'currency' => $order_currency ) );
						$refund_id      = $refund_response->data->id;
						$refund_message = sprintf( __( 'Refunded %1$s. Refund ID: %2$s. Reason: %3$s', 'woo-scapa' ), $amount, $refund_id, $reason );
						$order->add_order_note( $refund_message );

						return true;
					}

				} else {

					$refund_response = json_decode( wp_remote_retrieve_body( $refund_request ) );

					if ( isset( $refund_response->message ) ) {
						return new WP_Error( 'error', $refund_response->message );
					} else {
						return new WP_Error( 'error', __( 'Can&#39;t process refund at the moment. Try again later.', 'woo-scapa' ) );
					}
				}

			}

		}

	}

	/**
	 * Checks if WC version is less than passed in version.
	 *
	 * @param string $version Version to check against.
	 *
	 * @return bool
	 */
	public function is_wc_lt( $version ) {
		return version_compare( WC_VERSION, $version, '<' );
	}

	/**
	 * Checks if autocomplete order is enabled for the payment method.
	 *
	 * @since 5.7
	 * @param WC_Order $order Order object.
	 * @return bool
	 */
	protected function is_autocomplete_order_enabled( $order ) {
		$autocomplete_order = false;

		$payment_method = $order->get_payment_method();

		$scapa_settings = get_option('woocommerce_' . $payment_method . '_settings');

		if ( isset( $scapa_settings['autocomplete_order'] ) && 'yes' === $scapa_settings['autocomplete_order'] ) {
			$autocomplete_order = true;
		}

		return $autocomplete_order;
	}

	/**
	 * Retrieve the payment channels configured for the gateway
	 *
	 * @since 5.7
	 * @param WC_Order $order Order object.
	 * @return array
	 */
	protected function get_gateway_payment_channels( $order ) {
		$payment_channels = $this->payment_channels;
		if ( empty( $payment_channels ) && ( 'scapa' !== $order->get_payment_method() ) ) {
			$payment_channels = array( 'card' );
		}

		/**
		 * Filter the list of payment channels.
		 *
		 * @param array $payment_channels A list of payment channels.
		 * @param string $id Payment method ID.
		 * @param WC_Order $order Order object.
		 * @since 5.8.2
		 */
		return apply_filters( 'wc_scapa_payment_channels', $payment_channels, $this->id, $order );
	}

	/**
	 * Retrieve a transaction from Scapa.
	 *
	 * @since 5.7.5
	 * @param $scapa_txn_ref
	 * @return false|mixed
	 */
	private function get_scapa_transaction( $scapa_txn_ref ) {

		$scapa_url = 'https://api.scapa.io/transaction/verify/' . $scapa_txn_ref;

		$headers = array(
			'Authorization' => 'Bearer ' . $this->secret_key,
		);

		$args = array(
			'headers' => $headers,
			'timeout' => 60,
		);

		$request = wp_remote_get( $scapa_url, $args );

		if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {
			return json_decode( wp_remote_retrieve_body( $request ) );
		}

		return false;
	}

	/**
	 * Get Scapa payment icon URL.
	 */
	public function get_logo_url() {

		$base_location = wc_get_base_location();

		if ( 'GH' === $base_location['country'] ) {
			$url = WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-gh.png', WC_SCAPA_MAIN_FILE ) );
		} elseif ( 'ZA' === $base_location['country'] ) {
			$url = WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-za.png', WC_SCAPA_MAIN_FILE ) );
		} elseif ( 'KE' === $base_location['country'] ) {
			$url = WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-ke.png', WC_SCAPA_MAIN_FILE ) );
		} elseif ( 'CI' === $base_location['country'] ) {
			$url = WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-civ.png', WC_SCAPA_MAIN_FILE ) );
		} else {
			$url = WC_HTTPS::force_https_url( plugins_url( 'assets/images/scapa-wc.png', WC_SCAPA_MAIN_FILE ) );
		}

		return apply_filters( 'wc_scapa_gateway_icon_url', $url, $this->id );
	}

	/**
	 * Check if an order contains a subscription.
	 *
	 * @param int $order_id WC Order ID.
	 *
	 * @return bool
	 */
	public function order_contains_subscription( $order_id ) {

		return function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) );

	}
}
