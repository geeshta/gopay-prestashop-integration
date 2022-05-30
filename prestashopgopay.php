<?php
/**
 * PrestaShop GoPay gateway integration
 *
 * @author    Argo22
 * @license   https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
 */

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
		$this->ps_versions_compliancy = [
			'min' => '1.5',
		];

		parent::__construct();

		$this->init();

		$this->displayName = $this->l( 'PrestaShop GoPay gateway' );
		$this->description = $this->l( 'PrestaShop and GoPay payment gateway integration' );

		$this->confirmUninstall = $this->l( 'Are you sure you want to uninstall PrestaShop GoPay gateway ?' );

		$this->limited_countries  = array( 'CZ' );
		$this->limited_currencies = array( 'CZK' );

		// Classes instantiation
		$this->prestashopGopayOptions = new PrestashopGopayOptions($this);
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
		return parent::install() &&
			$this->registerHook('payment');
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
								'query' => array(
									array( 'key' => 'CS', 'name' => 'Czech' ),
									array( 'key' => 'EN', 'name' => 'English' ),
								),
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
								'query' => $this->prestashopGopayOptions->supported_shipping_methods(),
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
								'query' => $this->prestashopGopayOptions->supported_countries(),
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
								'query' => $this->prestashopGopayOptions->supported_payment_methods(),
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
								'query' => $this->prestashopGopayOptions->supported_banks(),
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
}
