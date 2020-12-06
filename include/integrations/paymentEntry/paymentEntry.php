<?php
/*************************************************************************************************
 * Copyright 2020 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS customizations.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module    : Payment Entry Integration
 *  Version   : 1.0
 *  Author    : JPL TSolucio, S. L.
 *************************************************************************************************/

class corebos_paymentEntry {
	private $isactive = 0;
	private $stripeisActive = 0;
	private $paypalisActive = 0;
	const KEY_ISACTIVE = 'paymententry_isactive';
	const KEY_STRIPE_ISACTIVE = 'stripe_isactive';
	const KEY_PAYPAL_ISACTIVE = 'paypal_isactive';
	const STRIPE_DRIVER_NAME = 'stripe';
	const KEY_STRIPE_API_KEY = 'stripe_key';
	const PAYPAL_DRIVER_NAME = 'paypal';
	const KEY_PAYPAL_CLIENT_ID = 'paypal_client_id';
	const KEY_PAYPAL_SECRET_KEY = 'paypal_secret_key';
	const KEY_PAYPAL_API_URL = 'paypal_api_url';
	const KEY_STRIPE_API_URL = 'stripe_api_url';
	private $stripeKey = '';
	private $paypalClientID = '';
	private $paypalsecretKey = '';
	private $paypalApiUrl = '';
	private $stripeApiUrl = '';

	public function __construct() {
		$this->initGlobalScope();
	}

	public function initGlobalScope() {
		$this->isactive = coreBOS_Settings::getSetting(self::KEY_ISACTIVE, 0);
	}

	public function saveSettings(
		$isactive,
		$stripeisActive,
		$paypalisActive,
		$stripeKey,
		$paypalClientID,
		$paypalsecretKey,
		$paypalApiUrl,
		$stripeApiUrl
	) {
		coreBOS_Settings::setSetting(self::KEY_ISACTIVE, $isactive);
		coreBOS_Settings::setSetting(self::KEY_STRIPE_ISACTIVE, $stripeisActive);
		coreBOS_Settings::setSetting(self::KEY_PAYPAL_ISACTIVE, $paypalisActive);
		coreBOS_Settings::setSetting(self::KEY_STRIPE_API_KEY, $stripeKey);
		coreBOS_Settings::setSetting(self::KEY_PAYPAL_CLIENT_ID, $paypalClientID);
		coreBOS_Settings::setSetting(self::KEY_PAYPAL_SECRET_KEY, $paypalsecretKey);
		coreBOS_Settings::setSetting(self::KEY_PAYPAL_API_URL, $paypalApiUrl);
		coreBOS_Settings::setSetting(self::KEY_STRIPE_API_URL, $stripeApiUrl);
	}

	public function getSettings() {
		return array(
			'paymententry_isactive' => coreBOS_Settings::getSetting(self::KEY_ISACTIVE, ''),
			'stripe_isactive' => coreBOS_Settings::getSetting(self::KEY_STRIPE_ISACTIVE, ''),
			'paypal_isactive' => coreBOS_Settings::getSetting(self::KEY_PAYPAL_ISACTIVE, ''),
			'stripe_key' => coreBOS_Settings::getSetting(self::KEY_STRIPE_API_KEY, ''),
			'paypal_client_id' => coreBOS_Settings::getSetting(self::KEY_PAYPAL_CLIENT_ID, ''),
			'paypal_secret_key' => coreBOS_Settings::getSetting(self::KEY_PAYPAL_SECRET_KEY, ''),
			'paypal_api_url' => coreBOS_Settings::getSetting(self::KEY_PAYPAL_API_URL, ''),
			'stripe_api_url' => coreBOS_Settings::getSetting(self::KEY_STRIPE_API_URL, ''),
		);
	}

	public function isActive($driverName = '') {
		$isactive = coreBOS_Settings::getSetting(self::KEY_ISACTIVE, 0);
		switch ($driverName) {
			case self::STRIPE_DRIVER_NAME:
				$stripeisActive = coreBOS_Settings::getSetting(self::KEY_STRIPE_ISACTIVE, '');
				return ($isactive==1 && $stripeisActive == 1);
				break;
			case self::PAYPAL_DRIVER_NAME:
				$paypalisActive =  coreBOS_Settings::getSetting(self::KEY_PAYPAL_ISACTIVE, '');
				return ($isactive==1 && $paypalisActive == 1);
			default:
				return ($isactive==1);
				break;
		}
	}

	public static function checkDriverConfiguration($driverName) {
		$driverName = self::sanitizeDriverNameValue($driverName);
		$isactive = coreBOS_Settings::getSetting(self::KEY_ISACTIVE, '0');
		switch ($driverName) {
			case 'stripe':
				$stripekey = coreBOS_Settings::getSetting(self::KEY_STRIPE_API_KEY, '');
				return ($isactive != '0' && !empty($stripekey));
				break;
			case 'paypal':
				$paypalkey = coreBOS_Settings::getSetting(self::KEY_PAYPAL_CLIENT_ID, '');
				return ($isactive != '0' && !empty($stripekey));
				break;
			default:
				return 0;
				break;
		}
	}

	public static function sanitizeDriverNameValue($driverName) {
		$driverName = strtolower(trim($driverName));
		return $driverName;
	}
}
?>