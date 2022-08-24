<?php

class AdminPrestaShopGoPayLogController extends ModuleAdminController
{
	public function __construct()
	{
		$this->db_log_table = 'gopay_log';

		parent::__construct();
	}

	public function initContent()
	{
		$this->display      = 'view';
		$this->meta_title   = 'PrestaShop GoPay Log';
		$this->show_toolbar = true;

		$this->context->controller->addJS( _MODULE_DIR_ . 'prestashopgopay/views/js/menu.js' );
		$this->context->controller->addCSS( _MODULE_DIR_ . 'prestashopgopay/views/css/menu.css' );

		parent::initContent();
	}

	public function initToolBarTitle()
	{
		$this->toolbar_title = 'Log';
	}

	public function initToolBar()
	{
		return true;
	}

	public function renderView() {

		$pagenum          = Tools::getValue( 'pagenum' );
		$log_table_filter = Tools::getValue( 'log_table_filter' );

		$rows = Db::getInstance()->executeS( sprintf(
			"SELECT COUNT(*) as num_rows FROM %s%s
			WHERE UPPER(CONCAT(order_id, transaction_id, message, created_at, log_level, log))
                REGEXP '[\w\W]*%s[\w\W]*'",
			_DB_PREFIX_,
			$this->db_log_table,
			pSQL( strtoupper( $log_table_filter ) )
		) );

		$results_per_page = 20;
		$number_of_rows   = $rows[0]['num_rows'];
		$number_of_pages  = ceil( $number_of_rows / $results_per_page );

		if ( null === $pagenum || false === $pagenum ) {
			$pagenum = 1;
		}

		$page_pagination = ( $pagenum - 1 ) * $results_per_page;
		$log_data        = $page_pagination >= 0 ? Db::getInstance()->executeS(
			sprintf(
				"SELECT * FROM %s%s WHERE UPPER(CONCAT(order_id, transaction_id, message, created_at, log_level, log))
                REGEXP '[\w\W]*%s[\w\W]*' ORDER BY created_at DESC LIMIT %d,%d",
				_DB_PREFIX_,
				$this->db_log_table,
				pSQL( strtoupper( $log_table_filter ) ),
				$page_pagination,
				$results_per_page
			)
		) : array();

		$head = array(
			'Id' => $this->module->l( 'Id' ), 'Order id' => $this->module->l( 'Order id' ),
			'Transaction id' => $this->module->l( 'Transaction id' ),
			'Message' => $this->module->l( 'Message' ),
			'Created at' => $this->module->l( 'Created at' ),
			'Log level' => $this->module->l( 'Log level' ), 'Log' => $this->module->l( 'Log' )
		);

		$this->context->smarty->assign([
			'head'             => $head,
			'log_data'         => $log_data,
			'orders_link'      => $this->context->link->getAdminLink( 'AdminOrders' ),
			'pagenum'          => $pagenum,
			'log_table_filter' => $log_table_filter,
			'number_of_pages'  => $number_of_pages,
		]);

		return $this->context->smarty->fetch('module:prestashopgopay/views/templates/admin/log.tpl');
	}

}