<?php

/**
 * Controller responsible for cheking
 * payment from GoPay
 *
 * @package   PrestaShop GoPay gateway
 * @author    argo22
 * @link      https://www.argo22.com
 * @copyright 2022 argo22
 * @since     1.0.0
 */

class PrestaShopGoPayPaymentModuleFrontController extends ModuleFrontController
{
	/**
	 * Init controller
	 *
	 * @since  1.0.0
	 */
	public function initContent() {
		parent::initContent();

		$this->setTemplate('module:prestashopgopay/views/templates/front/check_payment.tpl');
	}

	/**
	 * Check GoPay payment
	 *
	 * @since  1.0.0
	 */
	public function postProcess()
	{

		$link           = $this->context->link;
		$transaction_id = $_REQUEST['id'];

		$reference = Db::getInstance()->getValue(
			"SELECT order_reference FROM `prestashop`.`ps_order_payment` WHERE transaction_id = '" .
			$transaction_id . "';" );
		$id_order  = Db::getInstance()->getValue(
			"SELECT id_order FROM `prestashop`.`ps_orders` WHERE reference = '" .
			$reference . "';" );

		$order = new Order($id_order);

		PrestashopGopayApi::check_payment_status( $order, $transaction_id );

		Tools::redirect( $link->getPageLink('order-detail', true) . '&id_order=' . $id_order );

	}
}