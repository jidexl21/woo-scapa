<?php

/**
 * Class Tbz_WC_Scapa_Custom_Gateway.
 */
class WC_Gateway_Custom_Scapa extends WC_Gateway_Scapa_Subscriptions {

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
 
		$this->form_fields = array(
			'enabled'                          => array(
				'title'       => __( 'Enable/Disable', 'woo-scapa' ),
				/* translators: payment method title */
				'label'       => sprintf( __( 'Enable Scapa - %s', 'woo-scapa' ), $this->title ),
				'type'        => 'checkbox',
				'description' => __( 'Enable this gateway as a payment option on the checkout page.', 'woo-scapa' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'title'                            => array(
				'title'       => __( 'Title', 'woo-scapa' ),
				'type'        => 'text',
				'description' => __( 'This controls the payment method title which the user sees during checkout.', 'woo-scapa' ),
				'desc_tip'    => true,
				'default'     => __( 'Scapa', 'woo-scapa' ),
			),
			'description'                      => array(
				'title'       => __( 'Description', 'woo-scapa' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the payment method description which the user sees during checkout.', 'woo-scapa' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'payment_page'                     => array(
				'title'       => __( 'Payment Option', 'woo-scapa' ),
				'type'        => 'select',
				'description' => __( 'Popup shows the payment popup on the page while Redirect will redirect the customer to Scapa to make payment.', 'woo-scapa' ),
				'default'     => '',
				'desc_tip'    => false,
				'options'     => array(
					''         => __( 'Select One', 'woo-scapa' ),
					// 'inline'   => __( 'Popup', 'woo-scapa' ),
					'redirect' => __( 'Redirect', 'woo-scapa' ),
				),
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
				'class'       => __( 'woocommerce_scapa_subaccount_code', 'woo-scapa' ),
				'default'     => '',
			),
			'split_payment_transaction_charge' => array(
				'title'             => __( 'Split Payment Transaction Charge', 'woo-scapa' ),
				'type'              => 'number',
				'description'       => __( 'A flat fee to charge the subaccount for this transaction, in Naira (&#8358;). This overrides the split percentage set when the subaccount was created. Ideally, you will need to use this if you are splitting in flat rates (since subaccount creation only allows for percentage split). e.g. 100 for a &#8358;100 flat fee.', 'woo-scapa' ),
				'class'             => 'woocommerce_scapa_split_payment_transaction_charge',
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
			'payment_channels'                 => array(
				'title'             => __( 'Payment Channels', 'woo-scapa' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-scapa-payment-channels',
				'description'       => __( 'The payment channels enabled for this gateway', 'woo-scapa' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->channels(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select payment channels', 'woo-scapa' ),
				),
			),
			'cards_allowed'                    => array(
				'title'             => __( 'Allowed Card Brands', 'woo-scapa' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-scapa-cards-allowed',
				'description'       => __( 'The card brands allowed for this gateway. This filter only works with the card payment channel.', 'woo-scapa' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->card_types(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select card brands', 'woo-scapa' ),
				),
			),
			'banks_allowed'                    => array(
				'title'             => __( 'Allowed Banks Card', 'woo-scapa' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-scapa-banks-allowed',
				'description'       => __( 'The banks whose card should be allowed for this gateway. This filter only works with the card payment channel.', 'woo-scapa' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->banks(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select banks', 'woo-scapa' ),
				),
			),
			'payment_icons'                    => array(
				'title'             => __( 'Payment Icons', 'woo-scapa' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select wc-scapa-payment-icons',
				'description'       => __( 'The payment icons to be displayed on the checkout page.', 'woo-scapa' ),
				'default'           => '',
				'desc_tip'          => true,
				'select_buttons'    => true,
				'options'           => $this->payment_icons(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select payment icons', 'woo-scapa' ),
				),
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

	}

	/**
	 * Admin Panel Options.
	 */
	public function admin_options() {

		$scapa_settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=scapa' );
		$checkout_settings_url = admin_url( 'admin.php?page=wc-settings&tab=checkout' );
		?>

		<h2>
			<?php
			/* translators: payment method title */
			printf( __( 'Scapa - %s', 'woo-scapa' ), esc_attr( $this->title ) );
			?>
			<?php
			if ( function_exists( 'wc_back_link' ) ) {
				wc_back_link( __( 'Return to payments', 'woo-scapa' ), $checkout_settings_url );
			}
			?>
		</h2>

		<h4>
			<?php
			/* translators: link to Scapa developers settings page */
			printf( __( 'Important: To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="%s" target="_blank" rel="noopener noreferrer">here</a> to the URL below', 'woo-scapa' ), 'https://dashboard.scapa.io/#/settings/developer' );
			?>
		</h4>

		<p style="color: red">
			<code><?php echo esc_url( WC()->api_request_url( 'Tbz_WC_Scapa_Webhook' ) ); ?></code>
		</p>

		<p>
			<?php
			/* translators: link to Scapa general settings page */
			printf( __( 'To configure your Scapa API keys and enable/disable test mode, do that <a href="%s">here</a>', 'woo-scapa' ), esc_url( $scapa_settings_url ) );
			?>
		</p>

		<?php

		if ( $this->is_valid_for_use() ) {

			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';

		} else {

			/* translators: disabled message */
			echo '<div class="inline error"><p><strong>' . sprintf( __( 'Scapa Payment Gateway Disabled: %s', 'woo-scapa' ), esc_attr( $this->msg ) ) . '</strong></p></div>';

		}

	}

	/**
	 * Payment Channels.
	 */
	public function channels() {

		return array(
			'card'          => __( 'Cards', 'woo-scapa' ),
			'bank'          => __( 'Pay with Bank', 'woo-scapa' ),
			'ussd'          => __( 'USSD', 'woo-scapa' ),
			'qr'            => __( 'QR', 'woo-scapa' ),
			'bank_transfer' => __( 'Bank Transfer', 'woo-scapa' ),
		);

	}

	/**
	 * Card Types.
	 */
	public function card_types() {

		return array(
			'visa'       => __( 'Visa', 'woo-scapa' ),
			'verve'      => __( 'Verve', 'woo-scapa' ),
			'mastercard' => __( 'Mastercard', 'woo-scapa' ),
		);

	}

	/**
	 * Banks.
	 */
	public function banks() {

		return array(
			'044'  => __( 'Access Bank', 'woo-scapa' ),
			'035A' => __( 'ALAT by WEMA', 'woo-scapa' ),
			'401'  => __( 'ASO Savings and Loans', 'woo-scapa' ),
			'023'  => __( 'Citibank Nigeria', 'woo-scapa' ),
			'063'  => __( 'Access Bank (Diamond)', 'woo-scapa' ),
			'050'  => __( 'Ecobank Nigeria', 'woo-scapa' ),
			'562'  => __( 'Ekondo Microfinance Bank', 'woo-scapa' ),
			'084'  => __( 'Enterprise Bank', 'woo-scapa' ),
			'070'  => __( 'Fidelity Bank', 'woo-scapa' ),
			'011'  => __( 'First Bank of Nigeria', 'woo-scapa' ),
			'214'  => __( 'First City Monument Bank', 'woo-scapa' ),
			'058'  => __( 'Guaranty Trust Bank', 'woo-scapa' ),
			'030'  => __( 'Heritage Bank', 'woo-scapa' ),
			'301'  => __( 'Jaiz Bank', 'woo-scapa' ),
			'082'  => __( 'Keystone Bank', 'woo-scapa' ),
			'014'  => __( 'MainStreet Bank', 'woo-scapa' ),
			'526'  => __( 'Parallex Bank', 'woo-scapa' ),
			'076'  => __( 'Polaris Bank Limited', 'woo-scapa' ),
			'101'  => __( 'Providus Bank', 'woo-scapa' ),
			'221'  => __( 'Stanbic IBTC Bank', 'woo-scapa' ),
			'068'  => __( 'Standard Chartered Bank', 'woo-scapa' ),
			'232'  => __( 'Sterling Bank', 'woo-scapa' ),
			'100'  => __( 'Suntrust Bank', 'woo-scapa' ),
			'032'  => __( 'Union Bank of Nigeria', 'woo-scapa' ),
			'033'  => __( 'United Bank For Africa', 'woo-scapa' ),
			'215'  => __( 'Unity Bank', 'woo-scapa' ),
			'035'  => __( 'Wema Bank', 'woo-scapa' ),
			'057'  => __( 'Zenith Bank', 'woo-scapa' ),
		);

	}

	/**
	 * Payment Icons.
	 */
	public function payment_icons() {

		return array(
			'verve'         => __( 'Verve', 'woo-scapa' ),
			'visa'          => __( 'Visa', 'woo-scapa' ),
			'mastercard'    => __( 'Mastercard', 'woo-scapa' ),
			'scapawhite' => __( 'Secured by Scapa White', 'woo-scapa' ),
			'scapablue'  => __( 'Secured by Scapa Blue', 'woo-scapa' ),
			'scapa-wc'   => __( 'Scapa Nigeria', 'woo-scapa' ),
			'scapa-gh'   => __( 'Scapa Ghana', 'woo-scapa' ),
			'access'        => __( 'Access Bank', 'woo-scapa' ),
			'alat'          => __( 'ALAT by WEMA', 'woo-scapa' ),
			'aso'           => __( 'ASO Savings and Loans', 'woo-scapa' ),
			'citibank'      => __( 'Citibank Nigeria', 'woo-scapa' ),
			'diamond'       => __( 'Access Bank (Diamond)', 'woo-scapa' ),
			'ecobank'       => __( 'Ecobank Nigeria', 'woo-scapa' ),
			'ekondo'        => __( 'Ekondo Microfinance Bank', 'woo-scapa' ),
			'enterprise'    => __( 'Enterprise Bank', 'woo-scapa' ),
			'fidelity'      => __( 'Fidelity Bank', 'woo-scapa' ),
			'firstbank'     => __( 'First Bank of Nigeria', 'woo-scapa' ),
			'fcmb'          => __( 'First City Monument Bank', 'woo-scapa' ),
			'gtbank'        => __( 'Guaranty Trust Bank', 'woo-scapa' ),
			'heritage'      => __( 'Heritage Bank', 'woo-scapa' ),
			'jaiz'          => __( 'Jaiz Bank', 'woo-scapa' ),
			'keystone'      => __( 'Keystone Bank', 'woo-scapa' ),
			'mainstreet'    => __( 'MainStreet Bank', 'woo-scapa' ),
			'parallex'      => __( 'Parallex Bank', 'woo-scapa' ),
			'polaris'       => __( 'Polaris Bank Limited', 'woo-scapa' ),
			'providus'      => __( 'Providus Bank', 'woo-scapa' ),
			'stanbic'       => __( 'Stanbic IBTC Bank', 'woo-scapa' ),
			'standard'      => __( 'Standard Chartered Bank', 'woo-scapa' ),
			'sterling'      => __( 'Sterling Bank', 'woo-scapa' ),
			'suntrust'      => __( 'Suntrust Bank', 'woo-scapa' ),
			'union'         => __( 'Union Bank of Nigeria', 'woo-scapa' ),
			'uba'           => __( 'United Bank For Africa', 'woo-scapa' ),
			'unity'         => __( 'Unity Bank', 'woo-scapa' ),
			'wema'          => __( 'Wema Bank', 'woo-scapa' ),
			'zenith'        => __( 'Zenith Bank', 'woo-scapa' ),
		);

	}

	/**
	 * Display the selected payment icon.
	 */
	public function get_icon() {
		$icon_html = '<img src="' . WC_HTTPS::force_https_url( WC_SCAPA_URL . '/assets/images/scapa.png' ) . '" alt="scapa" style="height: 40px; margin-right: 0.4em;margin-bottom: 0.6em;" />';
		$icon      = $this->payment_icons;

		if ( is_array( $icon ) ) {

			$additional_icon = '';

			foreach ( $icon as $i ) {
				$additional_icon .= '<img src="' . WC_HTTPS::force_https_url( WC_SCAPA_URL . '/assets/images/' . $i . '.png' ) . '" alt="' . $i . '" style="height: 40px; margin-right: 0.4em;margin-bottom: 0.6em;" />';
			}

			$icon_html .= $additional_icon;
		}

		return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
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

			$email = $order->get_billing_email();

			$amount = $order->get_total() * 100;

			$txnref = $order_id . '_' . time();

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

				$scapa_params['subaccount_code']     = $this->subaccount_code;
				$scapa_params['charges_account']     = $this->charges_account;
				$scapa_params['transaction_charges'] = $this->transaction_charges * 100;

			}

			/** This filter is documented in includes/class-wc-gateway-scapa.php */
			$payment_channels = apply_filters( 'wc_scapa_payment_channels', $this->payment_channels, $this->id, $order );

			if ( in_array( 'bank', $payment_channels, true ) ) {
				$scapa_params['bank_channel'] = 'true';
			}

			if ( in_array( 'card', $payment_channels, true ) ) {
				$scapa_params['card_channel'] = 'true';
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

			if ( $this->banks ) {

				$scapa_params['banks_allowed'] = $this->banks;

			}

			if ( $this->cards ) {

				$scapa_params['cards_allowed'] = $this->cards;

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

		wp_localize_script( 'wc_scapa', 'wc_scapa_params', $scapa_params );

	}

	/**
	 * Add custom gateways to the checkout page.
	 *
	 * @param $available_gateways
	 *
	 * @return mixed
	 */
	public function add_gateway_to_checkout( $available_gateways ) {

		if ( $this->enabled == 'no' ) {
			unset( $available_gateways[ $this->id ] );
		}

		return $available_gateways;

	}

	/**
	 * Check if the custom Scapa gateway is enabled.
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
}
