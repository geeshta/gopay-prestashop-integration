<?php

use GoPay\Http\Response;
use GoPay\Payments;

/**
 * PrestaShop GoPay API
 * Connect to the GoPay API using the GoPay's PHP SDK
 *
 * @package   PrestaShop GoPay gateway
 * @author    GoPay
 * @link      https://www.gopay.com/
 * @copyright 2022 GoPay
 * @since     1.0.0
 */

class PrestashopGopayApi
{

	/**
	 * Constructor for the plugin GoPay api
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
	}

	/**
	 * Decode GoPay response and add raw body if
	 * different from json property
	 *
	 * @param Response $response
	 *
	 * @since  1.0.0
	 */
	private static function decode_response( Response $response ): Response
	{
		$not_identical = ( json_decode( $response->__toString(), true ) != $response->json ) ||
			( empty( $response->__toString() ) != empty( $response->json ) );

		if ( $not_identical ) {
			$response->{'raw_body'} = filter_var( str_replace(
				'\n',
				' ',
				$response->__toString()
			), FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}

		return $response;
	}

	/**
	 * GoPay authentication
	 *
	 * @return Payments object
	 * @since  1.0.0
	 */
	public static function auth_gopay(): Payments
	{
		return GoPay\payments( array(
			'goid'             => Configuration::get( 'PRESTASHOPGOPAY_GOID' ),
			'clientId'         => Configuration::get( 'PRESTASHOPGOPAY_CLIENT_ID' ),
			'clientSecret'     => Configuration::get( 'PRESTASHOPGOPAY_CLIENT_SECRET' ),
			'isProductionMode' => !Configuration::get( 'PRESTASHOPGOPAY_TEST' ),
			'scope'            => GoPay\Definition\TokenScope::ALL,
			'language'         => Configuration::get( 'PRESTASHOPGOPAY_DEFAULT_LANGUAGE' ),
			'timeout'          => 30,
		) );
	}

	/**
	 * Get items info
	 *
	 * @param object $order order detail.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private static function get_items( $cartProducts ): array
	{
		$items = array();
		foreach ( $cartProducts as $item ) {
			$items[] = array(
				'type'        => 'ITEM',
				'name'        => $item['name'],
				'product_url' => Context::getContext()->link->getProductLink( $item['id_product'] ),
				'amount'      => $item['total_wt'] * 100,
				'count'       => $item['quantity'],
				'vat_rate'    => $item['rate'],
			);
		}

		return $items;
	}

	/**
	 * GoPay create payment
	 *
	 * @param Context $context          payment method.
	 * @param string  $gopay_payment_method   order detail.
	 * @param string  $moduleId module id
	 * @param string  $url URL of the payment page
	 *
	 * @return Response
	 * @since 1.0.0
	 */
	public static function create_payment( Context $context, string $gopay_payment_method, string $moduleId, string $url ):
	Response
	{
		$gopay        = self::auth_gopay();
		$cartProducts = $context->cart->getProducts();
		$customer     = new Customer( $context->cart->id_customer );
		$address      = new Address( $context->cart->id_address_invoice );
		$country      = new Country( $address->id_country );
		$currency     = new Currency( $context->cart->id_currency );

		$default_swift = '';
		foreach ( PrestashopGopayOptions::supported_banks() as $key => $value ) {
			if ( $gopay_payment_method == $value['key'] ) {
				$default_swift        = $gopay_payment_method;
				$gopay_payment_method = 'BANK_ACCOUNT';
			}
		}

		$default_payment_instrument = '';
		if ( !empty( $gopay_payment_method ) ) {
			$default_payment_instrument = $gopay_payment_method;
		}

		$items = self::get_items( $cartProducts );

		$notification_url = $url;
		$return_url       = $url;

		$callback = array(
			'return_url'       => $return_url,
			'notification_url' => $notification_url,
		);

		$contact = array(
			'first_name'   => $customer->firstname,
			'last_name'    => $customer->lastname,
			'email'        => $customer->email,
			'phone_number' => $address->phone,
			'city'         => $address->city,
			'street'       => $address->address1,
			'postal_code'  => $address->postcode,
			'country_code' => PrestashopGopayOptions::iso2_to_iso3()[ $country->iso_code ],
		);

		if ( !empty( $default_payment_instrument ) ) {
			$payer = array(
				'default_payment_instrument'  => $default_payment_instrument,
				'allowed_payment_instruments' => json_decode( Configuration::get( 'PRESTASHOPGOPAY_PAYMENT_METHODS' ) ),
				'allowed_swifts'              => json_decode( Configuration::get( 'PRESTASHOPGOPAY_BANKS' ) ),
				'contact'                     => $contact,
			);
			if ( ! empty( $default_swift ) ) {
				$payer['default_swift'] = $default_swift;
			}
		} else {
			$payer = array(
				'contact' => $contact,
			);
		}

		$additional_params = array(
			array(
				'name'  => 'id_cart',
				'value' => $context->cart->id,
			) );

		$language = PrestashopGopayOptions::country_to_language()[ $country->iso_code ];
		if ( !array_key_exists( $language, PrestashopGopayOptions::supported_languages() ) ) {
			$language = Configuration::get( 'PRESTASHOPGOPAY_DEFAULT_LANGUAGE' );
		}

		$total = (float) $context->cart->getOrderTotal( true, Cart::BOTH );

		$data = array(
			'payer'             => $payer,
			'amount'            => round( $total, 2 ) * 100,
			'currency'          => $currency->iso_code,
			'order_number'      => $context->cart->id,
			'order_description' => 'order',
			'items'             => $items,
			'additional_params' => $additional_params,
			'callback'          => $callback,
			'lang'              => $language,
		);

//		if ( !empty( $end_date ) ) {
//			$data['recurrence'] = array(
//				'recurrence_cycle'      => 'ON_DEMAND',
//				'recurrence_date_to'    => $end_date != 0 ? $end_date : date( 'Y-m-d', strtotime( '+5 years' ) ) );
//		}

		$response = $gopay->createPayment( $data );

		return self::decode_response( $response );
	}

	/**
	 * Check payment status
	 *
	 * @param Context $context
	 * @param string $GoPay_Transaction_id
	 *
	 * @since  1.0.0
	 */
	public static function check_payment_status( Context $context, string $GoPay_Transaction_id )
	{
		$gopay    = self::auth_gopay();
		$response = $gopay->getStatus( $GoPay_Transaction_id );

		if ( $response->statusCode != 200 ) {
			return;
		}

		switch ( $response->json['state'] ) {
			case 'PAID':
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->validateOrder(
					(int) $context->cart->id,
					(int) Configuration::get( 'PS_OS_WS_PAYMENT' ),
					(float) $context->cart->getOrderTotal( true, Cart::BOTH ),
					PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->displayName,
					null,
					array( 'transaction_id' => $GoPay_Transaction_id ),
					(int) $context->currency->id,
					false,
					$context->customer->secure_key
				);

				$order = Order::getByCartId( $context->cart->id );

				Tools::redirect( 'index.php?controller=order-confirmation&id_cart=' . (int) $context->cart->id . '&id_module=' .
					(int) PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->id . '&id_order=' .
					$order->id . '&key=' . $context->customer->secure_key );
				//Tools::redirect( $link->getPageLink('order-detail', true) . '&id_order=' . $id_order );

				break;
			case 'PAYMENT_METHOD_CHOSEN':
			case 'AUTHORIZED':
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->validateOrder(
					(int) $context->cart->id,
					(int) Configuration::get( 'GOPAY_OS_WAITING' ),
					(float) $context->cart->getOrderTotal( true, Cart::BOTH ),
					PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->displayName,
					null,
					array( 'transaction_id' => $GoPay_Transaction_id ),
					(int) $context->currency->id,
					false,
					$context->customer->secure_key
				);

				$order = Order::getByCartId( $context->cart->id );

				Tools::redirect( 'index.php?controller=order-confirmation&id_cart=' . (int) $context->cart->id . '&id_module=' .
					(int) PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->id . '&id_order=' .
					$order->id . '&key=' . $context->customer->secure_key );

				break;
			case 'CREATED':
			case 'TIMEOUTED':
			case 'CANCELED':
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->validateOrder(
					(int) $context->cart->id,
					(int) Configuration::get( 'PS_OS_ERROR' ),
					(float) $context->cart->getOrderTotal( true, Cart::BOTH ),
					PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->displayName,
					null,
					array( 'transaction_id' => $GoPay_Transaction_id ),
					(int) $context->currency->id,
					false,
					$context->customer->secure_key
				);

				$order = Order::getByCartId( $context->cart->id );

				Tools::redirect( 'index.php?controller=order-confirmation&id_cart=' . (int) $context->cart->id . '&id_module=' .
					(int) PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->id . '&id_order=' .
					$order->id . '&key=' . $context->customer->secure_key );

				break;
			case 'REFUNDED':
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->validateOrder(
					(int) $context->cart->id,
					(int) Configuration::get( 'PS_OS_REFUND' ),
					(float) $context->cart->getOrderTotal( true, Cart::BOTH ),
					PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->displayName,
					null,
					array( 'transaction_id' => $GoPay_Transaction_id ),
					(int) $context->currency->id,
					false,
					$context->customer->secure_key
				);

				$order = Order::getByCartId( $context->cart->id );

				Tools::redirect( 'index.php?controller=order-confirmation&id_cart=' . (int) $context->cart->id . '&id_module=' .
					(int) PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->id . '&id_order=' .
					$order->id . '&key=' . $context->customer->secure_key );

				break;
		}
	}

	/**
	 * Check payment methods and banks that
	 * are enabled on GoPay account.
	 *
	 * @param string
	 * @return array
	 * @since  1.0.0
	 */
	public static function check_enabled_on_GoPay( $currency ): array
	{
		$gopay = self::auth_gopay();

		$payment_methods = array();
		$banks           = array();
		$enabledPayments = $gopay->getPaymentInstruments( Configuration::get( 'PRESTASHOPGOPAY_GOID' ), $currency );

		if ( $enabledPayments->statusCode == 200 ) {
			foreach ( $enabledPayments->json['enabledPaymentInstruments'] as $key => $paymentMethod ) {
				$payment_methods[ $paymentMethod['paymentInstrument']
				] = array(
					'label' => PrestaShopGoPay::getInstanceByName(
						'prestashopgopay' )->l( $paymentMethod['label']['cs'] ),
					'image' => $paymentMethod['image']['normal'],
				);

				if ( $paymentMethod['paymentInstrument'] == 'BANK_ACCOUNT' ) {
					foreach ( $paymentMethod['enabledSwifts'] as $_ => $bank ) {
						$banks[ $bank['swift'] ] = array(
							'label'     => PrestaShopGoPay::getInstanceByName(
								'prestashopgopay' )->l( $bank['label']['cs'] ),
							'country'   => $bank['swift'] != 'OTHERS' ? substr($bank['swift'], 4, 2) : '',
							'image'     => $bank['image']['normal'] );
					}
				}
			}
		}

		return array( $payment_methods, $banks );
	}

	/**
	 * Refund payment
	 *
	 * @param string $transaction_id
	 * @param float  $amount
	 *
	 * @return Response $response
	 * @since  1.0.0
	 */
	public static function refund_payment( string $transaction_id, float $amount ): Response
	{
		$gopay    = self::auth_gopay();
		$response = $gopay->refundPayment( $transaction_id, $amount );

		return self::decode_response( $response );
	}

	/**
	 * Get status of the transaction
	 *
	 * @param int $transaction_id
	 * @since  1.0.0
	 */
	public static function get_status( int $transaction_id ): Response
	{
		$gopay    = self::auth_gopay();
		$response = $gopay->getStatus( $transaction_id );

		return self::decode_response( $response );
	}
}