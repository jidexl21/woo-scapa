jQuery( function( $ ) {
	'use strict';

	/**
	 * Object to handle Scapa admin functions.
	 */
	var wc_scapa_admin = {
		/**
		 * Initialize.
		 */
		init: function() {

			// Toggle api key settings.
			$( document.body ).on( 'change', '#woocommerce_scapa_testmode', function() {
				var test_secret_key = $( '#woocommerce_scapa_test_secret_key' ).parents( 'tr' ).eq( 0 ),
					test_public_key = $( '#woocommerce_scapa_test_public_key' ).parents( 'tr' ).eq( 0 ),
					live_secret_key = $( '#woocommerce_scapa_live_secret_key' ).parents( 'tr' ).eq( 0 ),
					live_public_key = $( '#woocommerce_scapa_live_public_key' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					test_secret_key.show();
					test_public_key.show();
					live_secret_key.hide();
					live_public_key.hide();
				} else {
					test_secret_key.hide();
					test_public_key.hide();
					live_secret_key.show();
					live_public_key.show();
				}
			} );

			$( '#woocommerce_scapa_testmode' ).change();

			$( document.body ).on( 'change', '.woocommerce_scapa_split_payment', function() {
				var subaccount_code = $( '.woocommerce_scapa_subaccount_code' ).parents( 'tr' ).eq( 0 ),
					subaccount_charge = $( '.woocommerce_scapa_split_payment_charge_account' ).parents( 'tr' ).eq( 0 ),
					transaction_charge = $( '.woocommerce_scapa_split_payment_transaction_charge' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					subaccount_code.show();
					subaccount_charge.show();
					transaction_charge.show();
				} else {
					subaccount_code.hide();
					subaccount_charge.hide();
					transaction_charge.hide();
				}
			} );

			$( '#woocommerce_scapa_split_payment' ).change();

			// Toggle Custom Metadata settings.
			$( '.wc-scapa-metadata' ).change( function() {
				if ( $( this ).is( ':checked' ) ) {
					$( '.wc-scapa-meta-order-id, .wc-scapa-meta-name, .wc-scapa-meta-email, .wc-scapa-meta-phone, .wc-scapa-meta-billing-address, .wc-scapa-meta-shipping-address, .wc-scapa-meta-products' ).closest( 'tr' ).show();
				} else {
					$( '.wc-scapa-meta-order-id, .wc-scapa-meta-name, .wc-scapa-meta-email, .wc-scapa-meta-phone, .wc-scapa-meta-billing-address, .wc-scapa-meta-shipping-address, .wc-scapa-meta-products' ).closest( 'tr' ).hide();
				}
			} ).change();

			// Toggle Bank filters settings.
			$( '.wc-scapa-payment-channels' ).on( 'change', function() {

				var channels = $( ".wc-scapa-payment-channels" ).val();

				if ( $.inArray( 'card', channels ) != '-1' ) {
					$( '.wc-scapa-cards-allowed' ).closest( 'tr' ).show();
					$( '.wc-scapa-banks-allowed' ).closest( 'tr' ).show();
				}
				else {
					$( '.wc-scapa-cards-allowed' ).closest( 'tr' ).hide();
					$( '.wc-scapa-banks-allowed' ).closest( 'tr' ).hide();
				}

			} ).change();

			$( ".wc-scapa-payment-icons" ).select2( {
				templateResult: formatScapaPaymentIcons,
				templateSelection: formatScapaPaymentIconDisplay
			} );

			$( '#woocommerce_scapa_test_secret_key, #woocommerce_scapa_live_secret_key' ).after(
				'<button class="wc-scapa-toggle-secret" style="height: 30px; margin-left: 2px; cursor: pointer"><span class="dashicons dashicons-visibility"></span></button>'
			);

			$( '.wc-scapa-toggle-secret' ).on( 'click', function( event ) {
				event.preventDefault();

				let $dashicon = $( this ).closest( 'button' ).find( '.dashicons' );
				let $input = $( this ).closest( 'tr' ).find( '.input-text' );
				let inputType = $input.attr( 'type' );

				if ( 'text' == inputType ) {
					$input.attr( 'type', 'password' );
					$dashicon.removeClass( 'dashicons-hidden' );
					$dashicon.addClass( 'dashicons-visibility' );
				} else {
					$input.attr( 'type', 'text' );
					$dashicon.removeClass( 'dashicons-visibility' );
					$dashicon.addClass( 'dashicons-hidden' );
				}
			} );
		}
	};

	function formatScapaPaymentIcons( payment_method ) {
		if ( !payment_method.id ) {
			return payment_method.text;
		}

		var $payment_method = $(
			'<span><img src=" ' + wc_scapa_admin_params.plugin_url + '/assets/images/' + payment_method.element.value.toLowerCase() + '.png" class="img-flag" style="height: 15px; weight:18px;" /> ' + payment_method.text + '</span>'
		);

		return $payment_method;
	};

	function formatScapaPaymentIconDisplay( payment_method ) {
		return payment_method.text;
	};

	wc_scapa_admin.init();

} );
