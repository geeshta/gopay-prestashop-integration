<?php

class AdminPrestaShopGoPayInfoController extends ModuleAdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function initContent()
	{
		$this->display      = 'view';
		$this->meta_title   = 'PrestaShop GoPay Info';
		$this->show_toolbar = true;

		$this->context->controller->addJS( _MODULE_DIR_ . 'prestashopgopay/views/js/menu.js' );
		$this->context->controller->addCSS( _MODULE_DIR_ . 'prestashopgopay/views/css/menu.css' );

		parent::initContent();
	}

	public function initToolBarTitle()
	{
		$this->toolbar_title = 'Info';
	}

	public function initToolBar()
	{
		return true;
	}

	public function renderView() {

		$settings_page = $this->context->link->getAdminLink( 'AdminModules', false ) . '&configure=' .
			$this->module->name . '&tab_module=' . $this->module->tab . '&module_name=' . $this->module->name .
			'&token=' . Tools::getAdminTokenLite( 'AdminModules' );

		$this->context->smarty->assign([
			'plugin_name'   => array( $this->module->l( 'Plugin Name' ), $this->module->displayName ),
			'version'       => array( $this->module->l( 'Version' ), $this->module->version ),
			'description'   => array( $this->module->l( 'Description' ), $this->module->description ),
			'author'        => array( $this->module->l( 'Author' ), $this->module->author ),
			'settings_page' => array( $this->module->l( 'Settings' ), $settings_page ),
		]);

		return $this->context->smarty->fetch('module:prestashopgopay/views/templates/admin/info.tpl');
	}
}