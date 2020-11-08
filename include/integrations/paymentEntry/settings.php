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

include_once 'include/integrations/paymentEntry/paymentEntry.php';
$smarty = new vtigerCRM_Smarty();
$cbosPaymentEntry = new corebos_paymentEntry();
global $adb, $current_user;
$isadmin = is_admin($current_user);
if ($isadmin && $_REQUEST['_op'] =='setpaymententry' && $_REQUEST['paymententry_isactive']=='on') {
	$isActive = ((empty($_REQUEST['paymententry_isactive']) || $_REQUEST['paymententry_isactive']!='on') ? 0 : 1);
	$stripeisActive = ((empty($_REQUEST['stripe_isactive']) || $_REQUEST['stripe_isactive']!='on') ? 0 : 1);
	$paypalisActive = ((empty($_REQUEST['paypal_isactive']) || $_REQUEST['paypal_isactive']!='on') ? 0 : 1);
	$stripeKey = (empty($_REQUEST['stripe_key']) ? '' : vtlib_purify($_REQUEST['stripe_key']));
	$paypalClientID = (empty($_REQUEST['paypal_client_id']) ? '' : vtlib_purify($_REQUEST['paypal_client_id']));
	$paypalsecretKey = (empty($_REQUEST['paypal_secret_key']) ? '' : vtlib_purify($_REQUEST['paypal_secret_key']));
	$paypalApiUrl = (empty($_REQUEST['paypal_api_url']) ? '' : vtlib_purify($_REQUEST['paypal_api_url']));
	$stripeApiUrl = (empty($_REQUEST['stripe_api_url']) ? '' : vtlib_purify($_REQUEST['stripe_api_url']));
	$cbosPaymentEntry->saveSettings(
		$isActive,
		$stripeisActive,
		$paypalisActive,
		$stripeKey,
		$paypalClientID,
		$paypalsecretKey,
		$paypalApiUrl,
		$stripeApiUrl
	);
}

$smarty->assign('TITLE_MESSAGE', getTranslatedString('Payment Entry Activation', $currentModule));
$cbosPaymentEntrySettings = $cbosPaymentEntry->getSettings();
$smarty->assign('stripeKey', $cbosPaymentEntrySettings['stripe_key']);
$smarty->assign('paypalClientID', $cbosPaymentEntrySettings['paypal_client_id']);
$smarty->assign('paypalsecretKey', $cbosPaymentEntrySettings['paypal_secret_key']);
$smarty->assign('paypalApiUrl', $cbosPaymentEntrySettings['paypal_api_url']);
$smarty->assign('stripeApiUrl', $cbosPaymentEntrySettings['stripe_api_url']);
$smarty->assign('isActive', $cbosPaymentEntry->isActive());
$smarty->assign('stripeisActive', $cbosPaymentEntry->isActive('stripe'));
$smarty->assign('paypalisActive', $cbosPaymentEntry->isActive('paypal'));
$smarty->assign('APP', $app_strings);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('MODULE', $currentModule);
$smarty->assign('SINGLE_MOD', 'SINGLE_'.$currentModule);
$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
$smarty->assign('THEME', $theme);
include 'include/integrations/forcedButtons.php';
$smarty->assign('CHECK', $tool_buttons);
$smarty->assign('ISADMIN', $isadmin);
$smarty->display('modules/Utilities/paymentEntry.tpl');