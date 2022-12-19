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

		$this->context->controller->addJS( _PS_MODULE_DIR_ . 'prestashopgopay/views/js/menu.js' );
		$this->context->controller->addCSS( _PS_MODULE_DIR_ . 'prestashopgopay/views/css/menu.css' );

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
			'plugin_name'   => $this->module->displayName,
			'version'       => $this->module->version,
			'description'   => $this->module->description,
			'author'        => $this->module->author,
			'settings_page' => $settings_page,
		]);

		return $this->context->smarty->fetch( _PS_MODULE_DIR_. 'prestashopgopay/views/templates/admin/info.tpl' );
	}
}