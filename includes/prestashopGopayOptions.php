<?php

/**
 * PrestaShop GoPay gateway lists of supported
 * options default
 * Lists of supported options for languages,
 * payment methods, banks, shipping methods,
 * countries and currencies
 *
 * @package   PrestaShop GoPay gateway
 * @author    argo22
 * @link      https://www.argo22.com
 * @copyright 2022 argo22
 * @since     1.0.0
 */

class PrestashopGopayOptions
{
	/**
	 * Contructor
	 *
	 */
	public function __construct()
	{
	}

	/**
	 * Return supported currencies that
	 * can be used in the gateway
	 *
	 * @return array
	 */
	public static function supported_currencies(): array
	{
		return array(
			'CZK' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Czech koruna' ),
			'EUR' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Euro' ),
			'PLN' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Polish złoty' ),
			'USD' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'United States dollar' ),
			'GBP' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Pound sterling' ),
			'HUF' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Hungarian forint' ),
			'RON' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Romanian lei' ),
			'BGN' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Bulgarian lev' ),
			'HRK' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Croatian kuna' ),
		);
	}

	/**
	 * Return supported languages that
	 * can be used in the gateway
	 *
	 * @return array
	 */
	public static function supported_languages(): array
	{
		return array(
			'CS' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Czech' ),
			'SK' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Slovak' ),
			'EN' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'English' ),
			'DE' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'German' ),
			'RU' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Russian' ),
			'PL' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Polish' ),
			'HU' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Hungarian' ),
			'RO' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Romanian' ),
			'BG' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Bulgarian' ),
			'HR' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Croatian' ),
			'IT' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Italian' ),
			'FR' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'French' ),
			'ES' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Spanish' ),
			'UK' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Ukrainian' ),
		);
	}

	/**
	 * Return countries as keys and the language spoken
	 * in the country as values.
	 * If country has more than one spoken language than
	 * the one with the highest number of speakers is returned.
	 *
	 * @return array
	 */
	public static function country_to_language(): array
	{
		// Extracted from geonames.org (http://download.geonames.org/export/dump/countryInfo.txt)
		return array(
			'AD' => 'CA', 'AE' => 'AR', 'AF' => 'FA', 'AG' => 'EN', 'AI' => 'EN', 'AL' => 'SQ', 'AM' => 'HY',
			'AO' => 'PT', 'AR' => 'ES', 'AS' => 'EN', 'AT' => 'DE', 'AU' => 'EN', 'AW' => 'NL', 'AX' => 'SV',
			'AZ' => 'AZ', 'BA' => 'BS', 'BB' => 'EN', 'BD' => 'BN', 'BE' => 'NL', 'BF' => 'FR', 'BG' => 'BG',
			'BH' => 'AR', 'BI' => 'FR', 'BJ' => 'FR', 'BL' => 'FR', 'BM' => 'EN', 'BN' => 'MS', 'BO' => 'ES',
			'BQ' => 'NL', 'BR' => 'PT', 'BS' => 'EN', 'BT' => 'DZ', 'BW' => 'EN', 'BY' => 'BE', 'BZ' => 'EN',
			'CA' => 'EN', 'CC' => 'MS', 'CD' => 'FR', 'CF' => 'FR', 'CG' => 'FR', 'CH' => 'DE', 'CI' => 'FR',
			'CK' => 'EN', 'CL' => 'ES', 'CM' => 'EN', 'CN' => 'ZH', 'CO' => 'ES', 'CR' => 'ES', 'CU' => 'ES',
			'CV' => 'PT', 'CW' => 'NL', 'CX' => 'EN', 'CY' => 'EL', 'CZ' => 'CS', 'DE' => 'DE', 'DJ' => 'FR',
			'DK' => 'DA', 'DM' => 'EN', 'DO' => 'ES', 'DZ' => 'AR', 'EC' => 'ES', 'EE' => 'ET', 'EG' => 'AR',
			'EH' => 'AR', 'ER' => 'AA', 'ES' => 'ES', 'ET' => 'AM', 'FI' => 'FI', 'FJ' => 'EN', 'FK' => 'EN',
			'FM' => 'EN', 'FO' => 'FO', 'FR' => 'FR', 'GA' => 'FR', 'GB' => 'EN', 'GD' => 'EN', 'GE' => 'KA',
			'GF' => 'FR', 'GG' => 'EN', 'GH' => 'EN', 'GI' => 'EN', 'GL' => 'KL', 'GM' => 'EN', 'GN' => 'FR',
			'GP' => 'FR', 'GQ' => 'ES', 'GR' => 'EL', 'GS' => 'EN', 'GT' => 'ES', 'GU' => 'EN', 'GW' => 'PT',
			'GY' => 'EN', 'HK' => 'ZH', 'HN' => 'ES', 'HR' => 'HR', 'HT' => 'HT', 'HU' => 'HU', 'ID' => 'ID',
			'IE' => 'EN', 'IL' => 'HE', 'IM' => 'EN', 'IN' => 'EN', 'IO' => 'EN', 'IQ' => 'AR', 'IR' => 'FA',
			'IS' => 'IS', 'IT' => 'IT', 'JE' => 'EN', 'JM' => 'EN', 'JO' => 'AR', 'JP' => 'JA', 'KE' => 'EN',
			'KG' => 'KY', 'KH' => 'KM', 'KI' => 'EN', 'KM' => 'AR', 'KN' => 'EN', 'KP' => 'KO', 'KR' => 'KO',
			'XK' => 'SQ', 'KW' => 'AR', 'KY' => 'EN', 'KZ' => 'KK', 'LA' => 'LO', 'LB' => 'AR', 'LC' => 'EN',
			'LI' => 'DE', 'LK' => 'SI', 'LR' => 'EN', 'LS' => 'EN', 'LT' => 'LT', 'LU' => 'LB', 'LV' => 'LV',
			'LY' => 'AR', 'MA' => 'AR', 'MC' => 'FR', 'MD' => 'RO', 'ME' => 'SR', 'MF' => 'FR', 'MG' => 'FR',
			'MH' => 'MH', 'MK' => 'MK', 'ML' => 'FR', 'MM' => 'MY', 'MN' => 'MN', 'MO' => 'ZH', 'MP' => 'FIL',
			'MQ' => 'FR', 'MR' => 'AR', 'MS' => 'EN', 'MT' => 'MT', 'MU' => 'EN', 'MV' => 'DV', 'MW' => 'NY',
			'MX' => 'ES', 'MY' => 'MS', 'MZ' => 'PT', 'NA' => 'EN', 'NC' => 'FR', 'NE' => 'FR', 'NF' => 'EN',
			'NG' => 'EN', 'NI' => 'ES', 'NL' => 'NL', 'NO' => 'NO', 'NP' => 'NE', 'NR' => 'NA', 'NU' => 'NIU',
			'NZ' => 'EN', 'OM' => 'AR', 'PA' => 'ES', 'PE' => 'ES', 'PF' => 'FR', 'PG' => 'EN', 'PH' => 'TL',
			'PK' => 'UR', 'PL' => 'PL', 'PM' => 'FR', 'PN' => 'EN', 'PR' => 'EN', 'PS' => 'AR', 'PT' => 'PT',
			'PW' => 'PAU', 'PY' => 'ES', 'QA' => 'AR', 'RE' => 'FR', 'RO' => 'RO', 'RS' => 'SR', 'RU' => 'RU',
			'RW' => 'RW', 'SA' => 'AR', 'SB' => 'EN', 'SC' => 'EN', 'SD' => 'AR', 'SS' => 'EN', 'SE' => 'SV',
			'SG' => 'CMN', 'SH' => 'EN', 'SI' => 'SL', 'SJ' => 'NO', 'SK' => 'SK', 'SL' => 'EN', 'SM' => 'IT',
			'SN' => 'FR', 'SO' => 'SO', 'SR' => 'NL', 'ST' => 'PT', 'SV' => 'ES', 'SX' => 'NL', 'SY' => 'AR',
			'SZ' => 'EN', 'TC' => 'EN', 'TD' => 'FR', 'TF' => 'FR', 'TG' => 'FR', 'TH' => 'TH', 'TJ' => 'TG',
			'TK' => 'TKL', 'TL' => 'TET', 'TM' => 'TK', 'TN' => 'AR', 'TO' => 'TO', 'TR' => 'TR', 'TT' => 'EN',
			'TV' => 'TVL', 'TW' => 'ZH', 'TZ' => 'SW', 'UA' => 'UK', 'UG' => 'EN', 'UM' => 'EN', 'US' => 'EN',
			'UY' => 'ES', 'UZ' => 'UZ', 'VA' => 'LA', 'VC' => 'EN', 'VE' => 'ES', 'VG' => 'EN', 'VI' => 'EN',
			'VN' => 'VI', 'VU' => 'BI', 'WF' => 'WLS', 'WS' => 'SM', 'YE' => 'AR', 'YT' => 'FR', 'ZA' => 'ZU',
			'ZM' => 'EN', 'ZW' => 'EN', 'CS' => 'CU', 'AN' => 'NL',
		);
	}

	/**
	 * Get languages by country
	 *
	 * @param string $country country code
	 * @return array
	 */
	public static function get_languages_by_country( $country )
	{
		$locales = ResourceBundle::getLocales('');

		$matches = array();
		foreach ( $locales as $key => $locale ) {
			if ( Locale::getRegion( $locale ) == $country ) {
				$matches[ Locale::getPrimaryLanguage( $locale ) ][] = $locale;
			}
		}

		return $matches;

	}

	/**
	 * Return supported countries where
	 * the gateway can be available
	 *
	 * @return array
	 */
	public static function supported_countries(): array
	{
		global $cookie;

		$countries = array();
		foreach ( Country::getCountries( (int)$cookie->id_lang, true ) as $key => $country_info ) {
			$countries[] = array( 'key' => $country_info['iso_code'], 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( $country_info['name'] ) );
		}

		return $countries;
	}

	/**
	 * Return supported shipping methods that
	 * the gateway can use
	 *
	 * @return array
	 */
	public static function supported_shipping_methods(): array
	{
		global $cookie;

		$carriers = array();
		foreach ( Carrier::getCarriers( (int)$cookie->id_lang, true ) as $key => $carrier_info ) {
			$carriers[] = array( 'key' => $carrier_info['id_carrier'], 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( $carrier_info['name'] ) );
		}

		return $carriers;
	}

	/**
	 * Return supported payment methods that
	 * the gateway can use
	 *
	 * @return array
	 */
	public static function supported_payment_methods(): array
	{
		// Supported payment methods according to https://doc.gopay.com/#payment-instrument
		$payment_methods = array(
			array( 'key' => 'PAYMENT_CARD', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Payment card' ) ),
			array( 'key' => 'BANK_ACCOUNT', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Bank account' ) ),
			array( 'key' => 'GPAY', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Google Pay' ) ),
			array( 'key' => 'APPLE_PAY', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Apple Pay' ) ),
			array( 'key' => 'GOPAY', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'GoPay wallet' ) ),
			array( 'key' => 'PAYPAL', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'PayPal wallet' ) ),
			array( 'key' => 'MPAYMENT', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'mPlatba (mobile payment)' ) ),
			array( 'key' => 'PRSMS', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Premium SMS' ) ),
			array( 'key' => 'PAYSAFECARD', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'PaySafeCard coupon' ) ),
			array( 'key' => 'BITCOIN', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Bitcoin wallet' ) ),
			array( 'key' => 'CLICK_TO_PAY', 'name' =>
				PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Click to Pay' ) ),
		);

//		$options = get_option( 'woocommerce_wc_gopay_gateway_settings' ,  array() );
//		$key = 'option_gopay_payment_methods';
//
//		return !empty( $options ) && array_key_exists( $key, $options ) && !empty( $options[ $key ] ) ?
//			$options[ $key ] : $payment_methods ;
		return $payment_methods;
	}

	/**
	 * Return supported banks for bank payment that
	 * the gateway can use
	 *
	 * @return array
	 */
	public static function supported_banks(): array
	{

		// Supported banks according to https://doc.gopay.com/#swift
		$banks = array(
			array(
				'key'   => 'GIBACZPX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Česká Spořitelna' ) . ' CZ',
			),
			array(
				'key'   => 'KOMBCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Komerční Banka' ) . ' CZ',
			),
			array(
				'key'   => 'RZBCCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Raiffeisenbank' ) . ' CZ',
			),
			array(
				'key'   => 'FIOBCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'FIO Banka' ) . ' CZ',
			),
			array(
				'key'   => 'BACXCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'UniCredit Bank' ) . ' CZ',
			),
			array(
				'key'   => 'BREXCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'mBank' ) . ' CZ',
			),
			array(
				'key'   => 'CEKOCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'ČSOB' ) . ' CZ',
			),
			array(
				'key'   => 'CEKOCZPP-ERA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Poštovní Spořitelna' ) . ' CZ',
			),
			array(
				'key'   => 'AGBACZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Moneta Money Bank' ) . ' CZ',
			),
			array(
				'key'   => 'AIRACZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'AirBank' ) . ' CZ',
			),
			array(
				'key'   => 'EQBKCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'EQUA Bank' ) . ' CZ',
			),
			array(
				'key'   => 'INGBCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'ING Bank' ) . ' CZ',
			),
			array(
				'key'   => 'EXPNCZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Expobank' ) . ' CZ',
			),
			array(
				'key'   => 'OBKLCZ2X',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'OberBank AG' ) . ' CZ',
			),
			array(
				'key'   => 'SUBACZPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Všeobecná Úvěrová Banka - pobočka Praha' ) . ' CZ',
			),
			array(
				'key'   => 'TATRSKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Tatra Banka' ) . ' SK',
			),
			array(
				'key'   => 'SUBASKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Všeobecná Úverová Banka' ) . ' SK',
			),
			array(
				'key'   => 'UNCRSKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'UniCredit Bank' ) . ' SK',
			),
			array(
				'key'   => 'GIBASKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Slovenská Sporiteľňa' ) . ' SK',
			),
			array(
				'key'   => 'POBNSKBA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Poštová Banka' ) . ' SK',
			),
			array(
				'key'   => 'OTPVSKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'OTP Banka' ) . ' SK',
			),
			array(
				'key'   => 'KOMASK2X',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Prima Banka' ) . ' SK',
			),
			array(
				'key'   => 'CITISKBA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Citibank Europe' ) . ' SK',
			),
			array(
				'key'   => 'FIOZSKBA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'FIO Banka' ) . ' SK',
			),
			array(
				'key'   => 'INGBSKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'ING Wholesale Banking' ) . ' SK',
			),
			array(
				'key'   => 'BREXSKBX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'mBank' ) . ' SK',
			),
			array(
				'key'   => 'JTBPSKBA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'J&T Banka' ) . ' SK',
			),
			array(
				'key'   => 'OBKLSKBA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'OberBank AG' ) . ' SK',
			),
			array(
				'key'   => 'BSLOSK22',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Privatbanka' ) . ' SK',
			),
			array(
				'key'   => 'BFKKSKBB',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'BKS Bank AG' ) . ' SK',
			),
			array(
				'key'   => 'GBGCPLPK',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Getin Bank' ) . ' PL',
			),
			array(
				'key'   => 'NESBPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Nest Bank' ) . ' PL',
			),
			array(
				'key'   => 'VOWAPLP9',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Volkswagen Bank' ) . ' PL',
			),
			array(
				'key'   => 'CITIPLPX',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Citi handlowy' ) . ' PL',
			),
			array(
				'key'   => 'WBKPPLPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Santander' ) . ' PL',
			),
			array(
				'key'   => 'BIGBPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Millenium Bank' ) . ' PL',
			),
			array(
				'key'   => 'EBOSPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Bank Ochrony Srodowiska' ) . ' PL',
			),
			array(
				'key'   => 'PKOPPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Pekao Bank' ) . ' PL',
			),
			array(
				'key'   => 'PPABPLPK',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'BNP Paribas' ) . ' PL',
			),
			array(
				'key'   => 'BPKOPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'OWSZECHNA KASA OSZCZEDNOSCI BANK POLSK' ) . ' PL',
			),
			array(
				'key'   => 'AGRIPLPR',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Credit Agricole Banka Polska' ) . ' PL',
			),
			array(
				'key'   => 'GBGCPLPK-NOB',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Noble Bank' ) . ' PL',
			),
			array(
				'key'   => 'POLUPLPR',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'BPS/Bank Nowy BFG' ) . ' PL',
			),
			array(
				'key'   => 'BREXPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'mBank' ) . ' PL',
			),
			array(
				'key'   => 'INGBPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'ING Bank' ) . ' PL',
			),
			array(
				'key'   => 'ALBPPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Alior' ) . ' PL',
			),
			array(
				'key'   => 'IEEAPLPA',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'IdeaBank' ) . ' PL',
			),
			array(
				'key'   => 'POCZPLP4',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Pocztowy24' ) . ' PL',
			),
			array(
				'key'   => 'IVSEPLPP',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Plus Bank' ) . ' PL',
			),
			array(
				'key'   => 'TOBAPLPW',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Toyota Bank' ) . ' PL',
			),
			array(
				'key'   => 'OTHERS',
				'name' => PrestaShopGoPay::getInstanceByName( 'prestashopgopay' )->l( 'Another bank' ),
			),
		);

//		$options = get_option( 'woocommerce_wc_gopay_gateway_settings' ,  array() );
//		$key = 'option_gopay_banks';
//
//		return !empty( $options ) && array_key_exists( $key, $options ) && !empty( $options[ $key ] ) ?
//			$options[ $key ] : $banks ;
		return $banks;
	}

	/**
	 * Return iso 2 as keys and iso 3 equivalence as values
	 *
	 * @return array
	 */
	public static function iso2_to_iso3(): array
	{
		// Extracted from geonames.org (http://download.geonames.org/export/dump/countryInfo.txt)
		return array(
			'AD' => 'AND', 'AE' => 'ARE', 'AF' => 'AFG', 'AG' => 'ATG', 'AI' => 'AIA',
			'AL' => 'ALB', 'AM' => 'ARM', 'AO' => 'AGO', 'AQ' => 'ATA', 'AR' => 'ARG',
			'AS' => 'ASM', 'AT' => 'AUT', 'AU' => 'AUS', 'AW' => 'ABW', 'AX' => 'ALA',
			'AZ' => 'AZE', 'BA' => 'BIH', 'BB' => 'BRB', 'BD' => 'BGD', 'BE' => 'BEL',
			'BF' => 'BFA', 'BG' => 'BGR', 'BH' => 'BHR', 'BI' => 'BDI', 'BJ' => 'BEN',
			'BL' => 'BLM', 'BM' => 'BMU', 'BN' => 'BRN', 'BO' => 'BOL', 'BQ' => 'BES',
			'BR' => 'BRA', 'BS' => 'BHS', 'BT' => 'BTN', 'BV' => 'BVT', 'BW' => 'BWA',
			'BY' => 'BLR', 'BZ' => 'BLZ', 'CA' => 'CAN', 'CC' => 'CCK', 'CD' => 'COD',
			'CF' => 'CAF', 'CG' => 'COG', 'CH' => 'CHE', 'CI' => 'CIV', 'CK' => 'COK',
			'CL' => 'CHL', 'CM' => 'CMR', 'CN' => 'CHN', 'CO' => 'COL', 'CR' => 'CRI',
			'CU' => 'CUB', 'CV' => 'CPV', 'CW' => 'CUW', 'CX' => 'CXR', 'CY' => 'CYP',
			'CZ' => 'CZE', 'DE' => 'DEU', 'DJ' => 'DJI', 'DK' => 'DNK', 'DM' => 'DMA',
			'DO' => 'DOM', 'DZ' => 'DZA', 'EC' => 'ECU', 'EE' => 'EST', 'EG' => 'EGY',
			'EH' => 'ESH', 'ER' => 'ERI', 'ES' => 'ESP', 'ET' => 'ETH', 'FI' => 'FIN',
			'FJ' => 'FJI', 'FK' => 'FLK', 'FM' => 'FSM', 'FO' => 'FRO', 'FR' => 'FRA',
			'GA' => 'GAB', 'GB' => 'GBR', 'GD' => 'GRD', 'GE' => 'GEO', 'GF' => 'GUF',
			'GG' => 'GGY', 'GH' => 'GHA', 'GI' => 'GIB', 'GL' => 'GRL', 'GM' => 'GMB',
			'GN' => 'GIN', 'GP' => 'GLP', 'GQ' => 'GNQ', 'GR' => 'GRC', 'GS' => 'SGS',
			'GT' => 'GTM', 'GU' => 'GUM', 'GW' => 'GNB', 'GY' => 'GUY', 'HK' => 'HKG',
			'HM' => 'HMD', 'HN' => 'HND', 'HR' => 'HRV', 'HT' => 'HTI', 'HU' => 'HUN',
			'ID' => 'IDN', 'IE' => 'IRL', 'IL' => 'ISR', 'IM' => 'IMN', 'IN' => 'IND',
			'IO' => 'IOT', 'IQ' => 'IRQ', 'IR' => 'IRN', 'IS' => 'ISL', 'IT' => 'ITA',
			'JE' => 'JEY', 'JM' => 'JAM', 'JO' => 'JOR', 'JP' => 'JPN', 'KE' => 'KEN',
			'KG' => 'KGZ', 'KH' => 'KHM', 'KI' => 'KIR', 'KM' => 'COM', 'KN' => 'KNA',
			'KP' => 'PRK', 'KR' => 'KOR', 'KW' => 'KWT', 'KY' => 'CYM', 'KZ' => 'KAZ',
			'LA' => 'LAO', 'LB' => 'LBN', 'LC' => 'LCA', 'LI' => 'LIE', 'LK' => 'LKA',
			'LR' => 'LBR', 'LS' => 'LSO', 'LT' => 'LTU', 'LU' => 'LUX', 'LV' => 'LVA',
			'LY' => 'LBY', 'MA' => 'MAR', 'MC' => 'MCO', 'MD' => 'MDA', 'ME' => 'MNE',
			'MF' => 'MAF', 'MG' => 'MDG', 'MH' => 'MHL', 'MK' => 'MKD', 'ML' => 'MLI',
			'MM' => 'MMR', 'MN' => 'MNG', 'MO' => 'MAC', 'MP' => 'MNP', 'MQ' => 'MTQ',
			'MR' => 'MRT', 'MS' => 'MSR', 'MT' => 'MLT', 'MU' => 'MUS', 'MV' => 'MDV',
			'MW' => 'MWI', 'MX' => 'MEX', 'MY' => 'MYS', 'MZ' => 'MOZ', 'NA' => 'NAM',
			'NC' => 'NCL', 'NE' => 'NER', 'NF' => 'NFK', 'NG' => 'NGA', 'NI' => 'NIC',
			'NL' => 'NLD', 'NO' => 'NOR', 'NP' => 'NPL', 'NR' => 'NRU', 'NU' => 'NIU',
			'NZ' => 'NZL', 'OM' => 'OMN', 'PA' => 'PAN', 'PE' => 'PER', 'PF' => 'PYF',
			'PG' => 'PNG', 'PH' => 'PHL', 'PK' => 'PAK', 'PL' => 'POL', 'PM' => 'SPM',
			'PN' => 'PCN', 'PR' => 'PRI', 'PS' => 'PSE', 'PT' => 'PRT', 'PW' => 'PLW',
			'PY' => 'PRY', 'QA' => 'QAT', 'RE' => 'REU', 'RO' => 'ROU', 'RS' => 'SRB',
			'RU' => 'RUS', 'RW' => 'RWA', 'SA' => 'SAU', 'SB' => 'SLB', 'SC' => 'SYC',
			'SD' => 'SDN', 'SE' => 'SWE', 'SG' => 'SGP', 'SH' => 'SHN', 'SI' => 'SVN',
			'SJ' => 'SJM', 'SK' => 'SVK', 'SL' => 'SLE', 'SM' => 'SMR', 'SN' => 'SEN',
			'SO' => 'SOM', 'SR' => 'SUR', 'SS' => 'SSD', 'ST' => 'STP', 'SV' => 'SLV',
			'SX' => 'SXM', 'SY' => 'SYR', 'SZ' => 'SWZ', 'TC' => 'TCA', 'TD' => 'TCD',
			'TF' => 'ATF', 'TG' => 'TGO', 'TH' => 'THA', 'TJ' => 'TJK', 'TK' => 'TKL',
			'TL' => 'TLS', 'TM' => 'TKM', 'TN' => 'TUN', 'TO' => 'TON', 'TR' => 'TUR',
			'TT' => 'TTO', 'TV' => 'TUV', 'TW' => 'TWN', 'TZ' => 'TZA', 'UA' => 'UKR',
			'UG' => 'UGA', 'UM' => 'UMI', 'US' => 'USA', 'UY' => 'URY', 'UZ' => 'UZB',
			'VA' => 'VAT', 'VC' => 'VCT', 'VE' => 'VEN', 'VG' => 'VGB', 'VI' => 'VIR',
			'VN' => 'VNM', 'VU' => 'VUT', 'WF' => 'WLF', 'WS' => 'WSM', 'XK' => 'XKX',
			'YE' => 'YEM', 'YT' => 'MYT', 'ZA' => 'ZAF', 'ZM' => 'ZMB', 'ZW' => 'ZWE',
		);
	}
}
