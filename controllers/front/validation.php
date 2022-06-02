<?php

use GoPay\Http\Response;
use GoPay\Payments;

/**
* Controller responsible of order validation
* and payment creation
*
* @package   PrestaShop GoPay gateway
* @author    argo22
* @link      https://www.argo22.com
* @copyright 2022 argo22
* @since     1.0.0
*/

class PrestaShopGoPayValidationModuleFrontController extends ModuleFrontController
{
	/**
	 * Validate data, create payment
	 * and redirect to GoPay
	 *
	 * @since  1.0.0
	 */
	public function postProcess()
	{
		$cart     = $this->context->cart;
		$customer = new Customer( $cart->id_customer );

		if ( $cart->id_customer == 0 || $cart->id_address_delivery == 0 ||
			$cart->id_address_invoice == 0 || !$this->module->active) {
			Tools::redirect('index.php?controller=order&step=1');
		}

		if ( !($this->module instanceof PrestaShopGoPay || !Validate::isLoadedObject( $customer ) ) ) {
			Tools::redirect('index.php?controller=order&step=1');
		}

		$authorized = false;
		foreach ( Module::getPaymentModules() as $module ) {
			if ( $module['name'] == 'prestashopgopay' ) {
				$authorized = true;
				break;
			}
		}

		if (!$authorized) {
			die( $this->module->l( 'PrestaShop GoPay gateway is not available.', 'validation' ) );
		}

		$currency = $this->context->currency;
		$total = (float) $cart->getOrderTotal( true, Cart::BOTH );

		$this->module->validateOrder(
			(int) $cart->id,
			(int) Configuration::get( 'GOPAY_OS_WAITING' ),
			$total,
			$this->module->displayName,
			null,
			array(),
			(int) $currency->id,
			false,
			$customer->secure_key
		);

		$response = PrestashopGopayApi::create_payment( $this->context, array_key_exists( 'gopay_payment_method',
			$_REQUEST ) ? $_REQUEST['gopay_payment_method'] : '', $this->module->id );

		if ( $response->statusCode != 200 ) {
			Tools::redirect( 'index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int)
				$this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key );
		} else {
			$order = new Order( $this->module->currentOrder );
			$order->addOrderPayment( 0, $this->module->displayName, $response->json['id']);

			Tools::redirect( $response->json['gw_url'] );
		}
    }
}