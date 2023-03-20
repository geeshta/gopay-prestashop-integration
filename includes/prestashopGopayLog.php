<?php
/**
 * PrestaShop GoPay gateway log
 * Insert log into database
 *
 * @author    GoPay
 * @copyright 2022 GoPay
 * @license   https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
 * @see       https://www.gopay.com/
 * @since     1.0.0
 */

class PrestashopGopayLog
{

	/**
	 * Insert log into the database
	 *
	 * @param array $log Log text.
	 *
	 * @since  1.0.0
	 */
	public static function insert_log( array $log ) {

		$table_name = "gopay_log";
		$data       = array(
			'order_id'       => $log['order_id'],
			'transaction_id' => $log['transaction_id'],
			'message'        => $log['message'],
			'created_at'     => gmdate( 'Y-m-d H:i:s' ),
			'log_level'      => $log['log_level'],
			'log'            => json_encode( $log['log'] ),
		);
		$where      = "`order_id` = '" . $log['order_id'] .
			"' AND `transaction_id` = '" . $log['transaction_id'] .
			"' AND `message` = '" . $log['message'] . "'";

		$response = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
			"SELECT * FROM `" . _DB_PREFIX_ . $table_name . "` WHERE " . $where );
		if ( false === $response) {
			Db::getInstance()->insert( $table_name, $data );
		} else {
			Db::getInstance()->update( $table_name, $data, $where );
		}
	}

}