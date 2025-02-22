jQuery( function( $ ) {

	let scapa_submit = false;

	$( '#wc-scapa-form' ).hide();

	wcScapaFormHandler();

	jQuery( '#scapa-payment-button' ).click( function() {
		return wcScapaFormHandler();
	} );

	jQuery( '#scapa_form form#order_review' ).submit( function() {
		return wcScapaFormHandler();
	} );

	function wcScapaCustomFields() {

		let custom_fields = [
			{
				"display_name": "Plugin",
				"variable_name": "plugin",
				"value": "woo-scapa"
			}
		];

		if ( wc_scapa_params.meta_order_id ) {

			custom_fields.push( {
				display_name: "Order ID",
				variable_name: "order_id",
				value: wc_scapa_params.meta_order_id
			} );

		}

		if ( wc_scapa_params.meta_name ) {

			custom_fields.push( {
				display_name: "Customer Name",
				variable_name: "customer_name",
				value: wc_scapa_params.meta_name
			} );
		}

		if ( wc_scapa_params.meta_email ) {

			custom_fields.push( {
				display_name: "Customer Email",
				variable_name: "customer_email",
				value: wc_scapa_params.meta_email
			} );
		}

		if ( wc_scapa_params.meta_phone ) {

			custom_fields.push( {
				display_name: "Customer Phone",
				variable_name: "customer_phone",
				value: wc_scapa_params.meta_phone
			} );
		}

		if ( wc_scapa_params.meta_billing_address ) {

			custom_fields.push( {
				display_name: "Billing Address",
				variable_name: "billing_address",
				value: wc_scapa_params.meta_billing_address
			} );
		}

		if ( wc_scapa_params.meta_shipping_address ) {

			custom_fields.push( {
				display_name: "Shipping Address",
				variable_name: "shipping_address",
				value: wc_scapa_params.meta_shipping_address
			} );
		}

		if ( wc_scapa_params.meta_products ) {

			custom_fields.push( {
				display_name: "Products",
				variable_name: "products",
				value: wc_scapa_params.meta_products
			} );
		}

		return custom_fields;
	}

	function wcScapaCustomFilters() {

		let custom_filters = {};

		if ( wc_scapa_params.card_channel ) {

			if ( wc_scapa_params.banks_allowed ) {

				custom_filters[ 'banks' ] = wc_scapa_params.banks_allowed;

			}

			if ( wc_scapa_params.cards_allowed ) {

				custom_filters[ 'card_brands' ] = wc_scapa_params.cards_allowed;
			}

		}

		return custom_filters;
	}

	function wcPaymentChannels() {

		let payment_channels = [];

		if ( wc_scapa_params.bank_channel ) {
			payment_channels.push( 'bank' );
		}

		if ( wc_scapa_params.card_channel ) {
			payment_channels.push( 'card' );
		}

		if ( wc_scapa_params.ussd_channel ) {
			payment_channels.push( 'ussd' );
		}

		if ( wc_scapa_params.qr_channel ) {
			payment_channels.push( 'qr' );
		}

		if ( wc_scapa_params.bank_transfer_channel ) {
			payment_channels.push( 'bank_transfer' );
		}

		return payment_channels;
	}

	function wcScapaFormHandler() {

		$( '#wc-scapa-form' ).hide();

		if ( scapa_submit ) {
			scapa_submit = false;
			return true;
		}

		let $form = $( 'form#payment-form, form#order_review' ),
			scapa_txnref = $form.find( 'input.scapa_txnref' ),
			subaccount_code = '',
			charges_account = '',
			transaction_charges = '';

		scapa_txnref.val( '' );

		if ( wc_scapa_params.subaccount_code ) {
			subaccount_code = wc_scapa_params.subaccount_code;
		}

		if ( wc_scapa_params.charges_account ) {
			charges_account = wc_scapa_params.charges_account;
		}

		if ( wc_scapa_params.transaction_charges ) {
			transaction_charges = Number( wc_scapa_params.transaction_charges );
		}

		let amount = Number( wc_scapa_params.amount );

		let scapa_callback = function( transaction ) {
			$form.append( '<input type="hidden" class="scapa_txnref" name="scapa_txnref" value="' + transaction.reference + '"/>' );
			scapa_submit = true;

			$form.submit();

			$( 'body' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				},
				css: {
					cursor: "wait"
				}
			} );
		};

		let paymentData = {
			key: wc_scapa_params.key,
			email: wc_scapa_params.email,
			amount: amount,
			ref: wc_scapa_params.txnref,
			currency: wc_scapa_params.currency,
			subaccount: subaccount_code,
			bearer: charges_account,
			transaction_charge: transaction_charges,
			metadata: {
				custom_fields: wcScapaCustomFields(),
			},
			onSuccess: scapa_callback,
			onCancel: () => {
				$( '#wc-scapa-form' ).show();
				$( this.el ).unblock();
			}
		};

		if ( Array.isArray( wcPaymentChannels() ) && wcPaymentChannels().length ) {
			paymentData[ 'channels' ] = wcPaymentChannels();
			if ( !$.isEmptyObject( wcScapaCustomFilters() ) ) {
				paymentData[ 'metadata' ][ 'custom_filters' ] = wcScapaCustomFilters();
			}
		}

		const scapa = new ScapaPop();
		scapa.newTransaction( paymentData );
	}

} );