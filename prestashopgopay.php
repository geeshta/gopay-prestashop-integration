<?php
/**
 * PrestaShop GoPay gateway integration
 *
 * @author    Argo22
 * @license   https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
 */

declare(strict_types=1);

// If this file is called directly, abort.
// Preventing direct access to your PrestaShop.
if ( !defined( '_PS_VERSION_' ) ) {
	exit;
}

class PrestaShopGoPay extends PaymentModule
{
	/**
	 * Load required
	 *
	 * @since  1.0.0
	 */
	private function init()
	{
		require_once 'includes/prestashopGopayOptions.php';
		require_once 'includes/prestashopGopayApi.php';
	}

	/**
	 * Constructor for the gateway
	 *
	 * @since  1.0.0
	 */
	public function __construct()
	{
		$this->name                   = 'prestashopgopay';
		$this->tab                    = 'payments_gateways';
		$this->version                = '1.0.0';
		$this->author                 = 'Argo22';
		$this->need_instance          = 1;
		$this->ps_versions_compliancy = array(
			'min' => '1.5',
			'max' => _PS_VERSION_,
		);

		parent::__construct();

		$this->init();

		$this->displayName = $this->l( 'PrestaShop GoPay gateway' );
		$this->description = $this->l( 'PrestaShop and GoPay payment gateway integration' );

		$this->confirmUninstall = $this->l( 'Are you sure you want to uninstall PrestaShop GoPay gateway ?' );

//		$this->limited_countries  = array( 'CZ' );
//		$this->limited_currencies = array( 'CZK' );
	}

	/**
	 * Create a new order state
	 * for the GoPay payment module
	 *
	 * @return bool
	 */
	public function installOrderState()
	{
		if ( !Configuration::get( 'GOPAY_OS_WAITING' )
			|| !Validate::isLoadedObject( new OrderState( Configuration::get( 'GOPAY_OS_WAITING' ) ) )
		) {
			$order_state              = new OrderState();
			$order_state->color       = '#1e8dce';
			$order_state->module_name = $this->name;

			$order_state->name = [];
			foreach ( Language::getLanguages() as $language ) {
				$order_state->name[ $language['id_lang'] ] = 'Awaiting for GoPay payment';
			}

			if ( $order_state->add() ) {
				$source      = _PS_MODULE_DIR_ . 'prestashopgopay/logo.png';
				$destination = _PS_ROOT_DIR_ . '/img/os/' . (int) $order_state->id . '.gif';

				copy($source, $destination);
			}

			if ( Shop::isFeatureActive() ) {
				$shops = Shop::getShops();
				foreach ( $shops as $shop ) {
					Configuration::updateValue( 'GOPAY_OS_WAITING', (int) $order_state->id,
						false, null, (int) $shop['id_shop'] );
				}
			} else {
				Configuration::updateValue( 'GOPAY_OS_WAITING', (int) $order_state->id );
			}
		}

		return true;
	}

	/**
	 * True if the module is correctly installed,
	 * or false otherwise
	 *
	 * @return boolean
	 * @since  1.0.0
	 */
	public function install()
	{
		if ( !$this->installOrderState() ) {
			return false;
		}

		return parent::install() &&
			$this->registerHook( 'paymentOptions' );
	}

	/**
	 * True if the module is correctly uninstalled,
	 * or false otherwise
	 *
	 * @return boolean
	 * @since  1.0.0
	 */
	public function uninstall()
	{
		return parent::uninstall();
	}

	/**
	 * Module's configuration page
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public function getContent()
	{
		$this->update_payment_methods();
		$output = '';

		if ( Tools::isSubmit( 'submit' . $this->name ) ) {
			$output = $this->postProcess();
		}

		return $output . $this->displayForm();
	}

	/**
	 * Process and save config form data.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	protected function postProcess()
	{
		$form_values = Tools::getAllValues();

		foreach ( array_keys( $form_values ) as $key ) {
			if ( is_array( Tools::getValue( $key ) ) ) {
				Configuration::updateValue( $key, json_encode( Tools::getValue( $key ) ) );
				continue;
			}
			Configuration::updateValue( $key, Tools::getValue( $key ) );
		}

		if ( !array_key_exists( 'PRESTASHOPGOPAY_GOID', $form_values ) ||
			empty( $form_values['PRESTASHOPGOPAY_GOID'] )  ||
			 !array_key_exists( 'PRESTASHOPGOPAY_CLIENT_ID', $form_values ) ||
			empty( $form_values['PRESTASHOPGOPAY_CLIENT_ID'] )  ||
			 !array_key_exists( 'PRESTASHOPGOPAY_CLIENT_SECRET', $form_values ) ||
			empty( $form_values['PRESTASHOPGOPAY_CLIENT_SECRET'] )
		) {
			Configuration::updateValue( 'PRESTASHOPGOPAY_ENABLED', false );
			return $this->displayError( $this->l(
				'Inform goid, client id and secret to enable GoPay payment gateway and load the other options' ) );
		}

		return $this->displayConfirmation( $this->l( 'Settings updated' ) );
	}

	/**
	 * Configuration form
	 *
	 * @return string
	 * @since  1.0.0
	 */
	protected function displayForm()
	{
		$helper = new HelperForm();

		$helper->module                = $this;
		$helper->table                 = $this->table;
		$helper->name_controller       = $this->name;
		$helper->token                 = Tools::getAdminTokenLite( 'AdminModules' );
		$helper->currentIndex          = AdminController::$currentIndex . '&' . http_build_query(
			array( 'configure' => $this->name ) );
		$helper->submit_action         = 'submit' . $this->name;
		$helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT' );

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFormValues(),
			'languages'    => $this->context->controller->getLanguages(),
			'id_language'  => $this->context->language->id,
		);

		return $helper->generateForm( array( $this->getConfigForm() ) );
	}

	/**
	 * Structure of the configuration form
	 *
	 * @return array
	 * @since  1.0.0
	 */
	protected function getConfigForm()
	{
		if ( !empty( Configuration::get( 'PRESTASHOPGOPAY_GOID' ) ) &&
			!empty( Configuration::get( 'PRESTASHOPGOPAY_CLIENT_ID' ) ) &&
			!empty( Configuration::get( 'PRESTASHOPGOPAY_CLIENT_SECRET' ) )
		) {
			return array(
				'form' => array(
					'legend' => array(
						'title' => $this->l( 'Settings' ),
						'icon'  => 'icon-cogs',
					),
					'input' => array(
						array(
							'type'    => 'switch',
							'label'   => $this->l( 'Enable/Disable' ),
							'name'    => 'PRESTASHOPGOPAY_ENABLED',
							'is_bool' => true,
							'desc'    => $this->l( 'Enable GoPay payment gateway' ),
							'values'  => array(
								array(
									'id'    => 'active_on',
									'value' => true,
									'label' => $this->l( 'Enabled' )
								),
								array(
									'id'    => 'active_off',
									'value' => false,
									'label' => $this->l( 'Disabled' )
								),
							),
						),
						array(
							'type'     => 'text',
							'label'    => $this->l( 'Title' ),
							'name'     => 'PRESTASHOPGOPAY_TITLE',
							'size'     => 50,
							'required' => true,
//							'desc'    => $this->l(
//								'Name of the payment method that is displayed at the checkout' ),
						),
						array(
							'type'     => 'text',
							'label'    => $this->l( 'Description' ),
							'name'     => 'PRESTASHOPGOPAY_DESCRIPTION',
							'size'     => 50,
							'required' => true,
//							'desc'    => $this->l(
//								'Description of the payment method that is displayed at the checkout' ),
						),
						array(
							'type'     => 'text',
							'label'    => $this->l( 'GoId' ),
							'name'     => 'PRESTASHOPGOPAY_GOID',
							'size'     => 50,
							'required' => true,
						),
						array(
							'type'     => 'text',
							'label'    => $this->l( 'Client Id' ),
							'name'     => 'PRESTASHOPGOPAY_CLIENT_ID',
							'size'     => 50,
							'required' => true,
						),
						array(
							'type'     => 'text',
							'label'    => $this->l( 'Client secret' ),
							'name'     => 'PRESTASHOPGOPAY_CLIENT_SECRET',
							'size'     => 50,
							'required' => true,
						),
						array(
							'type'    => 'switch',
							'label'   => $this->l( 'Test mode' ),
							'name'    => 'PRESTASHOPGOPAY_TEST',
							'is_bool' => true,
							'desc'    => $this->l( 'Enable GoPay payment gateway test mode' ),
							'values'  => array(
								array(
									'id'    => 'active_on',
									'value' => true,
									'label' => $this->l( 'Enabled' )
								),
								array(
									'id'    => 'active_off',
									'value' => false,
									'label' => $this->l( 'Disabled' )
								),
							),
						),
						array(
							'type'    => 'select',
							'label'   => $this->l( 'Default language of the GoPay interface' ),
							'name'    => 'PRESTASHOPGOPAY_DEFAULT_LANGUAGE',
							'options' => array(
								'query' => PrestashopGopayOptions::supported_languages(),
								'id'   => 'key',
								'name' => 'name',
							),
						),
						array(
							'type'     => 'select',
							'label'    => $this->l( 'Enable shipping methods' ),
							'name'     => 'PRESTASHOPGOPAY_SHIPPING_METHODS[]',
							'multiple' => true,
							'options'  => array(
								'query' => PrestashopGopayOptions::supported_shipping_methods(),
								'id'   => 'key',
								'name' => 'name',
							),
						),
						array(
							'type'     => 'select',
							'label'    => $this->l( 'Enable countries' ),
							'name'     => 'PRESTASHOPGOPAY_COUNTRIES[]',
							'multiple' => true,
							'options'  => array(
								'query' => PrestashopGopayOptions::supported_countries(),
								'id'   => 'key',
								'name' => 'name',
							),
						),
						array(
							'type'    => 'switch',
							'label'   => $this->l( 'Payment method selection' ),
							'name'    => 'PRESTASHOPGOPAY_SIMPLIFIED',
							'is_bool' => true,
							'desc'    => $this->l( 'If enabled, customers cannot choose any specific payment' .
								' method at the checkout but they have to select the payment method once the ' .
								' GoPay payment gateway is invoked.' ),
							'values'  => array(
								array(
									'id'    => 'active_on',
									'value' => true,
									'label' => $this->l( 'Enabled' )
								),
								array(
									'id'    => 'active_off',
									'value' => false,
									'label' => $this->l( 'Disabled' )
								),
							),
						),
						array(
							'type'     => 'select',
							'label'    => $this->l( 'Enable GoPay payment methods' ),
							'name'     => 'PRESTASHOPGOPAY_PAYMENT_METHODS[]',
							'multiple' => true,
							'options'  => array(
								'query' => PrestashopGopayOptions::supported_payment_methods(),
								'id'   => 'key',
								'name' => 'name',
							),
						),
						array(
							'type'     => 'select',
							'label'    => $this->l( 'Enable banks' ),
							'name'     => 'PRESTASHOPGOPAY_BANKS[]',
							'multiple' => true,
							'options'  => array(
								'query' => PrestashopGopayOptions::supported_banks(),
								'id'   => 'key',
								'name' => 'name',
							),
						),
						array(
							'type'    => 'switch',
							'label'   => $this->l( 'Payment retry payment method' ),
							'name'    => 'PRESTASHOPGOPAY_PAYMENT_RETRY',
							'is_bool' => true,
							'desc'    => $this->l( 'If enabled, payment retry of a failed payment will be done' .
								' using the same payment method that was selected when customer was placing an order.' ),
							'values'  => array(
								array(
									'id'    => 'active_on',
									'value' => true,
									'label' => $this->l( 'Enabled' )
								),
								array(
									'id'    => 'active_off',
									'value' => false,
									'label' => $this->l( 'Disabled' )
								),
							),
						),
					),
					'submit' => array(
						'title' => $this->l( 'Save' ),
					),
				),
			);
		}

		return array(
			'form' => array(
				'legend' => array(
					'title' => $this->l(
						'Inform goid, client id and secret to enable GoPay payment gateway and load the other options' ),
					'icon'  => 'icon-cogs',
				),
				'input' => array(
					array(
						'type'     => 'text',
						'label'    => $this->l( 'GoId' ),
						'name'     => 'PRESTASHOPGOPAY_GOID',
						'size'     => 50,
						'required' => true,
					),
					array(
						'type'     => 'text',
						'label'    => $this->l( 'Client Id' ),
						'name'     => 'PRESTASHOPGOPAY_CLIENT_ID',
						'size'     => 50,
						'required' => true,
					),
					array(
						'type'     => 'text',
						'label'    => $this->l( 'Client secret' ),
						'name'     => 'PRESTASHOPGOPAY_CLIENT_SECRET',
						'size'     => 50,
						'required' => true,
					),
				),
				'submit' => array(
					'title' => $this->l( 'Save' ),
				),
			),
		);
	}

	/**
	 * Get values from the database
	 * to populate the inputs
	 *
	 * @return array
	 * @since  1.0.0
	 */
	protected function getConfigFormValues()
	{
		return array(
			'PRESTASHOPGOPAY_ENABLED'            => Configuration::get( 'PRESTASHOPGOPAY_ENABLED' ),
			'PRESTASHOPGOPAY_TITLE'              => Configuration::get( 'PRESTASHOPGOPAY_TITLE' ),
			'PRESTASHOPGOPAY_DESCRIPTION'        => Configuration::get( 'PRESTASHOPGOPAY_DESCRIPTION' ),
			'PRESTASHOPGOPAY_GOID'               => Configuration::get( 'PRESTASHOPGOPAY_GOID' ),
			'PRESTASHOPGOPAY_CLIENT_ID'          => Configuration::get( 'PRESTASHOPGOPAY_CLIENT_ID' ),
			'PRESTASHOPGOPAY_CLIENT_SECRET'      => Configuration::get( 'PRESTASHOPGOPAY_CLIENT_SECRET' ),
			'PRESTASHOPGOPAY_TEST'               => Configuration::get( 'PRESTASHOPGOPAY_TEST' ),
			'PRESTASHOPGOPAY_DEFAULT_LANGUAGE'   => Configuration::get( 'PRESTASHOPGOPAY_DEFAULT_LANGUAGE' ),
			'PRESTASHOPGOPAY_SHIPPING_METHODS[]' => json_decode( Configuration::get( 'PRESTASHOPGOPAY_SHIPPING_METHODS' ) ),
			'PRESTASHOPGOPAY_COUNTRIES[]'        => json_decode( Configuration::get( 'PRESTASHOPGOPAY_COUNTRIES' ) ),
			'PRESTASHOPGOPAY_SIMPLIFIED'         => Configuration::get( 'PRESTASHOPGOPAY_SIMPLIFIED' ),
			'PRESTASHOPGOPAY_PAYMENT_METHODS[]'  => json_decode( Configuration::get( 'PRESTASHOPGOPAY_PAYMENT_METHODS' ) ),
			'PRESTASHOPGOPAY_BANKS[]'            => json_decode( Configuration::get( 'PRESTASHOPGOPAY_BANKS' ) ),
			'PRESTASHOPGOPAY_PAYMENT_RETRY'      => Configuration::get( 'PRESTASHOPGOPAY_PAYMENT_RETRY' ),
		);
	}

	/**
	 * Check if PrestaShop GoPay payment
	 * method should be displayed
	 * and render the button
	 *
	 * @param array parameters
	 * @return array
	 * @since  1.0.0
	 */
	public function hookPaymentOptions($params)
	{
		if ( !$this->active ) {
			return array();
		}

		$option = new \PrestaShop\PrestaShop\Core\Payment\PaymentOption();
		$option->setCallToActionText( $this->l( Configuration::get( 'PRESTASHOPGOPAY_TITLE' ) ) )
			->setLogo( Media::getMediaPath( _PS_MODULE_DIR_.$this->name.'/gopay.png') );
		$option->setAction( $this->context->link->getModuleLink( $this->name, 'validation', array(), true ) );

		if ( !Configuration::get( 'PRESTASHOPGOPAY_SIMPLIFIED' ) ) {
			$option->setForm( $this->generateForm() );
		}

		$cart            = new Cart( $params['cart']->id );
		$address         = new Address( $cart->id_address_invoice );
		$invoice_country = new Country( $address->id_country );
		$currency_order  = new Currency( $cart->id_currency );
		$cartProducts    = $cart->getProducts();

		$enabled_countries        = json_decode( Configuration::get( 'PRESTASHOPGOPAY_COUNTRIES' ) );
		$enabled_shipping_methods = json_decode( Configuration::get( 'PRESTASHOPGOPAY_SHIPPING_METHODS' ) );

		// Check countries
		if ( empty( $invoice_country ) || empty( $enabled_countries ) ||
			!in_array( $invoice_country->iso_code, (array) $enabled_countries ) ) {
			return array();
		}
		// end check countries

		// Check currency matches one of the supported currencies
		if ( empty( $currency_order ) || !array_key_exists( $currency_order->iso_code,
				PrestashopGopayOptions::supported_currencies() )
		) {
			return array();
		}
		// end check currency

		// Check if all products are virtual
		$all_virtual_downloadable = true;
		foreach ( $cartProducts as $item ) {
			if ( !$item['is_virtual'] ) {
				$all_virtual_downloadable = false;
				break;
			}
		}

		if ( $all_virtual_downloadable ) {
			return [
				$option
			];
		}
		//end check virtual

		// Check shipping methods
		if ( empty( $cart ) || empty( $enabled_shipping_methods ) ||
			!in_array( $cart->id_carrier, (array) $enabled_shipping_methods ) ) {
			return array();
		}
		//end check shipping methods

		return [
			$option
		];
	}

	/**
	 * Generate form with list of payment methods
	 *
	 * @return false|string
	 * @since  1.0.0
	 */
	protected function generateForm()
	{
		$currency                 = new Currency( $this->context->cart->id_currency );
		$payment_methods_currency = json_decode( Configuration::get( 'GOPAY_PAYMENT_METHODS_' . $currency->iso_code ) );
		$banks_currency           = json_decode( Configuration::get( 'GOPAY_BANKS_' . $currency->iso_code ) );

		$decription              = Configuration::get( 'PRESTASHOPGOPAY_DESCRIPTION' );
		$enabled_payment_methods = array_flip( json_decode( Configuration::get( 'PRESTASHOPGOPAY_PAYMENT_METHODS' ) ) );
		$enabled_banks           = array_flip( json_decode( Configuration::get( 'PRESTASHOPGOPAY_BANKS' ) ) );

		// Intersection of all selected and the supported
		$supported_payment_methods = array();
		foreach ( $payment_methods_currency as $payment_method => $label_image ) {
			if ( array_key_exists( $payment_method, $enabled_payment_methods ) ) {
				$supported_payment_methods[ $payment_method ] = array( 'name' => $this->l( $label_image->label ),
					'image' => $label_image->image );
			}
		}
		if ( array_key_exists( 'BANK_ACCOUNT', $supported_payment_methods ) ) {
			unset( $supported_payment_methods['BANK_ACCOUNT'] );
			foreach ( $banks_currency as $swift => $label_image ) {
				if ( array_key_exists( $swift, $enabled_banks ) ) {
					$supported_payment_methods[ $swift ] = array( 'name' => $this->l( $label_image->label ),
						'image' => $label_image->image );
				}
			}
		}

		$this->context->smarty->assign([
			'action'          => $this->context->link->getModuleLink( $this->name, 'validation', array(), true ),
			'description'     => $decription,
			'payment_methods' => $supported_payment_methods,
		]);

		return $this->context->smarty->fetch( 'module:prestashopgopay/views/templates/front/payment_methods_form.tpl' );
	}

	/**
	 * Update payment methods and banks
	 *
	 * @since 1.0.0
	 */
	public function update_payment_methods()
	{
		if ( empty( Configuration::get( 'PRESTASHOPGOPAY_GOID' ) ) ) {
			return;
		}

		$this->check_enabled_on_GoPay();
	}

	/**
	 * Check payment methods and banks that
	 * are enabled on GoPay account.
	 *
	 * @since 1.0.0
	 */
	function check_enabled_on_GoPay()
	{
		$payment_methods = array();
		$banks = array();
		foreach ( PrestashopGopayOptions::supported_currencies() as $currency => $value ) {
			$supported        = PrestashopGopayApi::check_enabled_on_GoPay( $currency );
			$payment_methods  = $payment_methods + $supported[0];
			$banks            = $banks + $supported[1];

			Configuration::updateValue( 'GOPAY_PAYMENT_METHODS_' . $currency, json_encode($supported[0]) );
			Configuration::updateValue( 'GOPAY_BANKS_' . $currency, json_encode($supported[1]) );
		}

		if ( !empty( $payment_methods ) ) {
			Configuration::updateValue( 'OPTION_GOPAY_PAYMENT_METHODS', json_encode($payment_methods) );
		}
		if ( !empty( $banks ) ) {
			if ( array_key_exists( 'OTHERS', $banks ) ) {
				// Send 'Others' to the end
				$other = $banks['OTHERS'];
				unset( $banks['OTHERS'] );
				$banks['OTHERS'] = $other;
			}

			Configuration::updateValue( 'OPTION_GOPAY_BANKS', json_encode($banks) );
		}
	}
}
