<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Automattic\WooCommerce\StoreApi\Payments\PaymentContext;
use Automattic\WooCommerce\StoreApi\Payments\PaymentResult;

final class WC_Gateway_Scapa_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'scapa';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_scapa_settings', array() );

		add_action( 'woocommerce_rest_checkout_process_payment_with_context', array( $this, 'failed_payment_notice' ), 8, 2 );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		$payment_gateways_class = WC()->payment_gateways();
		$payment_gateways       = $payment_gateways_class->payment_gateways();
		return $payment_gateways['scapa']->is_available();
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$script_asset_path = plugins_url( '/assets/js/blocks/frontend/blocks.asset.php', WC_SCAPA_MAIN_FILE );
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => WC_SCAPA_VERSION,
			);

		$script_url = plugins_url( '/assets/js/blocks/frontend/blocks.js', WC_SCAPA_MAIN_FILE );

		wp_register_script(
			'wc-scapa-blocks',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'wc-scapa-blocks', 'woo-scapa', );
		}

		return array( 'wc-scapa-blocks' );
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		$payment_gateways_class = WC()->payment_gateways();
		$payment_gateways       = $payment_gateways_class->payment_gateways();
		$gateway                = $payment_gateways['scapa'];

		return array(
			'title'             => $this->get_setting( 'title' ),
			'description'       => $this->get_setting( 'description' ),
			'supports'          => array_filter( $gateway->supports, array( $gateway, 'supports' ) ),
			'allow_saved_cards' => $gateway->saved_cards && is_user_logged_in(),
			'logo_urls'         => array( $payment_gateways['scapa']->get_logo_url() ),
		);
	}

	/**
	 * Add failed payment notice to the payment details.
	 *
	 * @param PaymentContext $context Holds context for the payment.
	 * @param PaymentResult  $result  Result object for the payment.
	 */
	public function failed_payment_notice( PaymentContext $context, PaymentResult &$result ) {
		if ( 'scapa' === $context->payment_method ) {
			add_action(
				'wc_gateway_scapa_process_payment_error',
				function( $failed_notice ) use ( &$result ) {
					$payment_details                 = $result->payment_details;
					$payment_details['errorMessage'] = wp_strip_all_tags( $failed_notice );
					$result->set_payment_details( $payment_details );
				}
			);
		}
	}
}
